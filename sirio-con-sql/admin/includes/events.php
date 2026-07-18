<?php
require_once __DIR__ . '/../../includes/db.php';

function admin_events_is_writable(): bool
{
    return db_connect() !== null;
}

function admin_load_events(): array
{
    $conn = db_connect();
    if ($conn === null) {
        return [];
    }

    $result = $conn->query('SELECT * FROM events ORDER BY date ASC, created_at DESC');
    if ($result === false) {
        return [];
    }

    $events = [];
    while ($row = $result->fetch_assoc()) {
        $events[] = $row;
    }

    return $events;
}

function admin_save_events(array $events): bool
{
    return true;
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
    $conn = db_connect();
    if ($conn === null) {
        return false;
    }

    $stmt = $conn->prepare('INSERT INTO events (title, date, description, location) VALUES (?, ?, ?, ?)');
    $stmt->bind_param('ssss', $data['title'], $data['date'], $data['description'], $data['location']);
    $ok = $stmt->execute();
    $stmt->close();
    return $ok;
}

function admin_delete_event(int $eventId): bool
{
    $conn = db_connect();
    if ($conn === null) {
        return false;
    }

    $stmt = $conn->prepare('DELETE FROM events WHERE id = ?');
    $stmt->bind_param('i', $eventId);
    $ok = $stmt->execute();
    $stmt->close();
    return $ok;
}

function admin_format_event_date(string $date): string
{
    $ts = strtotime($date);
    if ($ts === false) {
        return $date;
    }
    return date('d/m/Y', $ts);
}
