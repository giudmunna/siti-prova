<?php
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/includes/db.php';

$conn = db_connect();
if ($conn === null) {
    fwrite(STDERR, "Impossibile connettersi a MySQL. Avvia XAMPP e riprova.\n");
    exit(1);
}

$conn->query('CREATE DATABASE IF NOT EXISTS `sirio` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci');
$conn->select_db(DB_NAME);

$schema = [];
$schema[] = "CREATE TABLE IF NOT EXISTS site_content (
    id INT AUTO_INCREMENT PRIMARY KEY,
    content_key VARCHAR(80) NOT NULL UNIQUE,
    content_value LONGTEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";

$schema[] = "CREATE TABLE IF NOT EXISTS courses (
    id INT AUTO_INCREMENT PRIMARY KEY,
    slug VARCHAR(120) NOT NULL UNIQUE,
    categoria VARCHAR(60) NOT NULL,
    titolo VARCHAR(180) NOT NULL,
    descrizione TEXT NOT NULL,
    immagine VARCHAR(255) NOT NULL DEFAULT 'assets/img/corsi/placeholder.svg',
    disponibile TINYINT(1) NOT NULL DEFAULT 1,
    ordine INT NOT NULL DEFAULT 0,
    orari_testo TEXT NULL,
    orari_json LONGTEXT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";

$schema[] = "CREATE TABLE IF NOT EXISTS reservations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    corso_id INT DEFAULT NULL,
    nome VARCHAR(120) NOT NULL,
    email VARCHAR(160) NOT NULL,
    telefono VARCHAR(40) DEFAULT '',
    messaggio TEXT NOT NULL,
    status ENUM('new','confirmed','closed') NOT NULL DEFAULT 'new',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_reservations_course FOREIGN KEY (corso_id) REFERENCES courses(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";

$schema[] = "CREATE TABLE IF NOT EXISTS events (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(200) NOT NULL,
    date DATE NOT NULL,
    description TEXT NOT NULL,
    location VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";

foreach ($schema as $sql) {
    if (!$conn->query($sql)) {
        fwrite(STDERR, "Errore schema: " . $conn->error . "\n");
        exit(1);
    }
}

$defaults = [
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

$json = json_encode($defaults, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
$stmt = $conn->prepare('INSERT INTO site_content (content_key, content_value) VALUES (?, ?) ON DUPLICATE KEY UPDATE content_value = VALUES(content_value)');
$key = 'site_data';
$stmt->bind_param('ss', $key, $json);
$stmt->execute();
$stmt->close();

$corsiSeed = [
    [
        'slug' => 'funzionale',
        'categoria' => 'fitness',
        'titolo' => 'Funzionale',
        'descrizione' => 'Allenamento a circuito per forza, resistenza e mobilità.',
        'immagine' => 'assets/img/corsi/funzionale.svg',
        'disponibile' => 1,
        'ordine' => 0,
        'orari_testo' => "Lunedì–Sabato\nPiù fasce giornaliere",
        'orari_json' => json_encode(['orari_settimana' => [['giorno' => 'Lunedì', 'fasce' => ['09:00', '18:30']], ['giorno' => 'Mercoledì', 'fasce' => ['18:30']], ['giorno' => 'Venerdì', 'fasce' => ['19:00']]]], JSON_UNESCAPED_UNICODE),
    ],
    [
        'slug' => 'ginnastica-corpo-libero',
        'categoria' => 'fitness',
        'titolo' => 'Ginnastica Corpo Libero',
        'descrizione' => 'Esercizi di tonicità, equilibrio e postura in un percorso accessibile a tutti.',
        'immagine' => 'assets/img/corsi/ginnastica-corpo-libero.svg',
        'disponibile' => 1,
        'ordine' => 1,
        'orari_testo' => 'Martedì e Giovedì · 18:00',
        'orari_json' => json_encode(['orari_settimana' => [['giorno' => 'Martedì', 'fasce' => ['18:00']], ['giorno' => 'Giovedì', 'fasce' => ['18:00']]]], JSON_UNESCAPED_UNICODE),
    ],
    [
        'slug' => 'ginnastica-dolce',
        'categoria' => 'fitness',
        'titolo' => 'Ginnastica Dolce',
        'descrizione' => 'Lavoro dolce e mirato per migliorare posture, flessibilità e benessere.',
        'immagine' => 'assets/img/corsi/ginnastica-dolce.svg',
        'disponibile' => 1,
        'ordine' => 2,
        'orari_testo' => 'Lunedì e Mercoledì · 10:30',
        'orari_json' => json_encode(['orari_settimana' => [['giorno' => 'Lunedì', 'fasce' => ['10:30']], ['giorno' => 'Mercoledì', 'fasce' => ['10:30']]]], JSON_UNESCAPED_UNICODE),
    ],
    [
        'slug' => 'pilates',
        'categoria' => 'fitness',
        'titolo' => 'Pilates',
        'descrizione' => 'Programma di stretching e rafforzamento per il corpo e la mente.',
        'immagine' => 'assets/img/corsi/pilates.svg',
        'disponibile' => 1,
        'ordine' => 3,
        'orari_testo' => 'Martedì e Venerdì · 17:30',
        'orari_json' => json_encode(['orari_settimana' => [['giorno' => 'Martedì', 'fasce' => ['17:30']], ['giorno' => 'Venerdì', 'fasce' => ['17:30']]]], JSON_UNESCAPED_UNICODE),
    ],
    [
        'slug' => 'total-body',
        'categoria' => 'fitness',
        'titolo' => 'Total Body',
        'descrizione' => 'Allenamento completo per forza, resistenza e postura.',
        'immagine' => 'assets/img/corsi/total-body.svg',
        'disponibile' => 1,
        'ordine' => 4,
        'orari_testo' => 'Lunedì e Giovedì · 19:00',
        'orari_json' => json_encode(['orari_settimana' => [['giorno' => 'Lunedì', 'fasce' => ['19:00']], ['giorno' => 'Giovedì', 'fasce' => ['19:00']]]], JSON_UNESCAPED_UNICODE),
    ],
    [
        'slug' => 'stretching',
        'categoria' => 'fitness',
        'titolo' => 'Stretching',
        'descrizione' => 'Sessioni dedicate a mobilità, rilassamento e recupero.',
        'immagine' => 'assets/img/corsi/stretching.svg',
        'disponibile' => 1,
        'ordine' => 5,
        'orari_testo' => 'Mercoledì · 20:00',
        'orari_json' => json_encode(['orari_settimana' => [['giorno' => 'Mercoledì', 'fasce' => ['20:00']]]], JSON_UNESCAPED_UNICODE),
    ],
    [
        'slug' => 'gag',
        'categoria' => 'fitness',
        'titolo' => 'GAG',
        'descrizione' => 'Workout dinamico per migliorare coordinazione e tono muscolare.',
        'immagine' => 'assets/img/corsi/gag.svg',
        'disponibile' => 1,
        'ordine' => 6,
        'orari_testo' => 'Giovedì · 18:30',
        'orari_json' => json_encode(['orari_settimana' => [['giorno' => 'Giovedì', 'fasce' => ['18:30']]]], JSON_UNESCAPED_UNICODE),
    ],
    [
        'slug' => 'predanza',
        'categoria' => 'danza',
        'titolo' => 'Pre-danza',
        'descrizione' => 'Percorso introduttivo per i più piccoli che vogliono avvicinarsi alla danza.',
        'immagine' => 'assets/img/corsi/predanza.svg',
        'disponibile' => 1,
        'ordine' => 7,
        'orari_testo' => 'Sabato · 10:00',
        'orari_json' => json_encode(['orari_settimana' => [['giorno' => 'Sabato', 'fasce' => ['10:00']]]], JSON_UNESCAPED_UNICODE),
    ],
    [
        'slug' => 'danza',
        'categoria' => 'danza',
        'titolo' => 'Danza',
        'descrizione' => 'Tecnica e coreografie per crescere nella danza classica e contemporanea.',
        'immagine' => 'assets/img/corsi/danza.svg',
        'disponibile' => 1,
        'ordine' => 8,
        'orari_testo' => 'Lunedì e Mercoledì · 17:15',
        'orari_json' => json_encode(['orari_settimana' => [['giorno' => 'Lunedì', 'fasce' => ['17:15']], ['giorno' => 'Mercoledì', 'fasce' => ['17:15']]]], JSON_UNESCAPED_UNICODE),
    ],
    [
        'slug' => 'caraibico',
        'categoria' => 'danza',
        'titolo' => 'Caraibico',
        'descrizione' => 'Energia, ritmo e movimento con lezioni vivaci e coinvolgenti.',
        'immagine' => 'assets/img/corsi/caraibico.svg',
        'disponibile' => 1,
        'ordine' => 9,
        'orari_testo' => 'Martedì · 20:00',
        'orari_json' => json_encode(['orari_settimana' => [['giorno' => 'Martedì', 'fasce' => ['20:00']]]], JSON_UNESCAPED_UNICODE),
    ],
    [
        'slug' => 'hip-hop',
        'categoria' => 'danza',
        'titolo' => 'Hip Hop',
        'descrizione' => 'Tecnica, ritmo e stile in un percorso moderno e coinvolgente.',
        'immagine' => 'assets/img/corsi/hip-hop.svg',
        'disponibile' => 1,
        'ordine' => 10,
        'orari_testo' => 'Giovedì · 20:30',
        'orari_json' => json_encode(['orari_settimana' => [['giorno' => 'Giovedì', 'fasce' => ['20:30']]]], JSON_UNESCAPED_UNICODE),
    ],
    [
        'slug' => 'pole-dance',
        'categoria' => 'danza',
        'titolo' => 'Pole Dance',
        'descrizione' => 'Lavoro di forza, fluidità e postura con un approccio tecnico.',
        'immagine' => 'assets/img/corsi/pole-dance.svg',
        'disponibile' => 1,
        'ordine' => 11,
        'orari_testo' => 'Venerdì · 20:00',
        'orari_json' => json_encode(['orari_settimana' => [['giorno' => 'Venerdì', 'fasce' => ['20:00']]]], JSON_UNESCAPED_UNICODE),
    ],
    [
        'slug' => 'judo',
        'categoria' => 'sport',
        'titolo' => 'Judo',
        'descrizione' => 'Disciplina marziale per bambini e ragazzi.',
        'immagine' => 'assets/img/corsi/judo.svg',
        'disponibile' => 1,
        'ordine' => 12,
        'orari_testo' => 'Lunedì e Mercoledì',
        'orari_json' => json_encode(['orari_settimana' => [['giorno' => 'Lunedì', 'fasce' => ['17:30']], ['giorno' => 'Mercoledì', 'fasce' => ['17:30']]]], JSON_UNESCAPED_UNICODE),
    ],
];

foreach ($corsiSeed as $corso) {
    $stmt = $conn->prepare('INSERT INTO courses (slug, categoria, titolo, descrizione, immagine, disponibile, ordine, orari_testo, orari_json) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?) ON DUPLICATE KEY UPDATE categoria = VALUES(categoria), titolo = VALUES(titolo), descrizione = VALUES(descrizione), immagine = VALUES(immagine), disponibile = VALUES(disponibile), ordine = VALUES(ordine), orari_testo = VALUES(orari_testo), orari_json = VALUES(orari_json)');
    $stmt->bind_param('sssssiiss', $corso['slug'], $corso['categoria'], $corso['titolo'], $corso['descrizione'], $corso['immagine'], $corso['disponibile'], $corso['ordine'], $corso['orari_testo'], $corso['orari_json']);
    $stmt->execute();
    $stmt->close();
}

printf("Database pronto. Sono stati creati i contenuti, i corsi e gli eventi in SQL.\n");
