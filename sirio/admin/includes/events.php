<?php

function admin_events_file(): string
{
    return dirname(__DIR__) . '/events.json';
}

function admin_events_is_writable(): bool
{
    $file = admin_events_file();
    if (is_file($file)) {
        return is_writable($file);
    }
    return is_writable(dirname($file));
}

function admin_load_events(): array
{
    $file = admin_events_file();
    if (!is_file($file)) {
        return [];
    }

    $raw = file_get_contents($file);
    if ($raw === false || trim($raw) === '') {
        return [];
    }

    $data = json_decode($raw, true);
    return is_array($data) ? $data : [];
}

function admin_save_events(array $events): bool
{
    $file = admin_events_file();
    $dir = dirname($file);

    if (!is_dir($dir) && !mkdir($dir, 0755, true)) {
        return false;
    }

    $json = json_encode(
        array_values($events),
        JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE
    );

    if ($json === false) {
        return false;
    }

    return file_put_contents($file, $json, LOCK_EX) !== false;
}

function admin_validate_event_input(array $input): array
{
    $errors = [];
    $title = trim($input['title'] ?? '');
    $date = trim($input['date'] ?? '');
    $description = trim($input['description'] ?? '');
    $location = trim($input['location'] ?? '');

    if ($title === '') {
        $errors[] = 'Inserisci il titolo dell\'evento.';
    } elseif (mb_strlen($title) > 200) {
        $errors[] = 'Il titolo è troppo lungo (max 200 caratteri).';
    }

    if ($date === '') {
        $errors[] = 'Seleziona la data dell\'evento.';
    } else {
        $dt = DateTime::createFromFormat('Y-m-d', $date);
        if (!$dt || $dt->format('Y-m-d') !== $date) {
            $errors[] = 'La data non è valida.';
        }
    }

    if ($description === '') {
        $errors[] = 'Inserisci una descrizione.';
    }

    if ($location === '') {
        $errors[] = 'Inserisci il luogo dell\'evento.';
    }

    return [
        'errors' => $errors,
        'data' => [
            'title' => $title,
            'date' => $date,
            'description' => $description,
            'location' => $location,
        ],
    ];
}

function admin_add_event(array $data): bool
{
    $events = admin_load_events();
    $events[] = [
        'id' => uniqid('evt_', true),
        'title' => $data['title'],
        'date' => $data['date'],
        'description' => $data['description'],
        'location' => $data['location'],
        'created_at' => date('c'),
    ];

    usort($events, function ($a, $b) {
        return strcmp($a['date'] ?? '', $b['date'] ?? '');
    });

    return admin_save_events($events);
}

function admin_format_event_date(string $date): string
{
    $ts = strtotime($date);
    if ($ts === false) {
        return $date;
    }
    return date('d/m/Y', $ts);
}
