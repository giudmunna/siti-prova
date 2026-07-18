<?php
require_once __DIR__ . '/db.php';

function getDefaultContent(): array
{
    return [
        'site' => [
            'name' => 'Sirio Fit & Dance - Alcamo',
            'legal_name' => 'SIRIO SSD ARL',
            'tagline' => 'Scuola di Danza e Fitness',
            'email' => 'SIRIOSSDARL@GMAIL.COM',
            'phone_mobile' => '379 1420911',
            'phone_landline' => '0924 040296',
            'address' => 'Via A. Pertini, 16 — 91011 Alcamo (TP)',
            'vat' => '02877410817',
        ],
        'home' => [
            'hero_title' => 'Sirio Fit & Dance',
            'hero_subtitle' => 'Passione • Movimento • Energia',
            'hero_button' => 'Scopri i nostri corsi',
        ],
        'chi_siamo' => [
            'title' => 'Chi Siamo',
            'text' => 'La Sirio Fit & Dance di Alcamo è una scuola di danza e fitness attiva da anni sul territorio.',
        ],
        'corsi' => [
            'title' => 'I Nostri Corsi',
            'categorie' => [
                'fitness' => 'Fitness e benessere',
                'danza' => 'Danza',
                'sport' => 'Sport e arti marziali',
            ],
        ],
        'galleria' => [
            'title' => 'Galleria',
        ],
        'eventi' => [
            'title' => 'Eventi & Spettacoli',
            'text' => 'Scopri i prossimi eventi, stage e spettacoli della Sirio Fit & Dance.',
        ],
        'contatti' => [
            'title' => 'Contattaci',
            'button' => 'Invia Messaggio',
        ],
    ];
}

function getContent(): array
{
    $conn = db_connect();
    if ($conn === null) {
        return getDefaultContent();
    }

    $stmt = $conn->prepare('SELECT content_value FROM site_content WHERE content_key = ? LIMIT 1');
    $key = 'site_data';
    $stmt->bind_param('s', $key);
    $stmt->execute();
    $row = $stmt->get_result()->fetch_assoc();
    $stmt->close();

    if ($row && !empty($row['content_value'])) {
        $decoded = json_decode($row['content_value'], true);
        if (is_array($decoded)) {
            return $decoded;
        }
    }

    $defaults = getDefaultContent();
    $json = json_encode($defaults, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    $stmt = $conn->prepare('INSERT INTO site_content (content_key, content_value) VALUES (?, ?) ON DUPLICATE KEY UPDATE content_value = VALUES(content_value)');
    $stmt->bind_param('ss', $key, $json);
    $stmt->execute();
    $stmt->close();

    return $defaults;
}

function normalizeCourseData(array $row): array
{
    $scheduleData = [];
    $orariJson = trim((string) ($row['orari_json'] ?? ''));
    if ($orariJson !== '') {
        $decoded = json_decode($orariJson, true);
        if (is_array($decoded)) {
            if (isset($decoded['orari_settimana']) && is_array($decoded['orari_settimana'])) {
                $scheduleData['orari_settimana'] = $decoded['orari_settimana'];
            }
            if (isset($decoded['orari']) && is_array($decoded['orari'])) {
                $scheduleData['orari'] = $decoded['orari'];
            }
        }
    }

    $orariTesto = trim((string) ($row['orari_testo'] ?? ''));
    if ($orariTesto !== '') {
        $scheduleData['orari_testo'] = $orariTesto;
    }

    return [
        'slug' => $row['slug'],
        'categoria' => $row['categoria'],
        'titolo' => $row['titolo'],
        'descrizione' => $row['descrizione'],
        'immagine' => $row['immagine'],
        'disponibile' => (bool) $row['disponibile'],
        'orari_testo' => $scheduleData['orari_testo'] ?? '',
        'orari_settimana' => $scheduleData['orari_settimana'] ?? [],
        'orari' => $scheduleData['orari'] ?? [],
    ];
}

function getCorsoBySlug(array $content, string $slug): ?array {
    $conn = db_connect();
    if ($conn === null) {
        return null;
    }

    $stmt = $conn->prepare('SELECT * FROM courses WHERE slug = ? LIMIT 1');
    $stmt->bind_param('s', $slug);
    $stmt->execute();
    $row = $stmt->get_result()->fetch_assoc();
    $stmt->close();

    if (!$row) {
        return null;
    }

    return normalizeCourseData($row);
}

function corsiByCategoria(array $content): array {
    $conn = db_connect();
    $gruppi = [];

    if ($conn !== null) {
        $result = $conn->query('SELECT * FROM courses WHERE disponibile = 1 ORDER BY ordine, titolo');
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $corso = normalizeCourseData($row);
                $gruppi[$corso['categoria']][] = $corso;
            }
            return $gruppi;
        }
    }

    return $gruppi;
}

function corsoUrl(array $corso): string {
    return 'corso.php?slug=' . urlencode($corso['slug'] ?? '');
}

function corsoHasSchedule(array $corso): bool {
    return !empty($corso['orari_settimana']) || !empty($corso['orari']) || !empty($corso['orari_testo']);
}

function phoneTel(string $number): string {
    $digits = preg_replace('/\D+/', '', $number);
    if ($digits !== '' && $digits[0] !== '0' && strlen($digits) <= 10) {
        $digits = '39' . $digits;
    }
    return '+' . ltrim($digits, '+');
}

function getAllCourses(): array
{
    $conn = db_connect();
    if ($conn === null) {
        return [];
    }

    $result = $conn->query('SELECT * FROM courses ORDER BY ordine, titolo');
    if ($result === false) {
        return [];
    }

    $corsi = [];
    while ($row = $result->fetch_assoc()) {
        $corso = normalizeCourseData($row);
        $corso['id'] = (int) $row['id'];
        $corsi[] = $corso;
    }

    return $corsi;
}

$content = getContent();
?>
