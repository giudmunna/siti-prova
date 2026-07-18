-- Dump del database Sirio
-- Esegui questo file in phpMyAdmin o con mysql -u root -p

CREATE DATABASE IF NOT EXISTS sirio CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE sirio;

CREATE TABLE IF NOT EXISTS site_content (
  id INT AUTO_INCREMENT PRIMARY KEY,
  content_key VARCHAR(80) NOT NULL UNIQUE,
  content_value LONGTEXT NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS courses (
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS reservations (
  id INT AUTO_INCREMENT PRIMARY KEY,
  corso_id INT DEFAULT NULL,
  nome VARCHAR(120) NOT NULL,
  email VARCHAR(160) NOT NULL,
  telefono VARCHAR(40) DEFAULT '',
  messaggio TEXT NOT NULL,
  status ENUM('new','confirmed','closed') NOT NULL DEFAULT 'new',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT fk_reservations_course FOREIGN KEY (corso_id) REFERENCES courses(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS events (
  id INT AUTO_INCREMENT PRIMARY KEY,
  title VARCHAR(200) NOT NULL,
  date DATE NOT NULL,
  description TEXT NOT NULL,
  location VARCHAR(255) NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO site_content (content_key, content_value) VALUES (
  'site_data',
  '{
    "site": {
      "name": "Sirio Fit & Dance - Alcamo",
      "legal_name": "SIRIO SSD ARL",
      "tagline": "Scuola di Danza e Fitness",
      "email": "SIRIOSSDARL@GMAIL.COM",
      "phone_mobile": "379 1420911",
      "phone_landline": "0924 040296",
      "address": "Via A. Pertini, 16 — 91011 Alcamo (TP)",
      "vat": "02877410817"
    },
    "home": {
      "hero_title": "Sirio Fit & Dance",
      "hero_subtitle": "Passione • Movimento • Energia",
      "hero_button": "Scopri i nostri corsi"
    },
    "chi_siamo": {
      "title": "Chi Siamo",
      "text": "La Sirio Fit & Dance di Alcamo è una scuola di danza e fitness attiva da anni sul territorio."
    },
    "corsi": {
      "title": "I Nostri Corsi",
      "categorie": {
        "fitness": "Fitness e benessere",
        "danza": "Danza",
        "sport": "Sport e arti marziali"
      }
    },
    "galleria": {
      "title": "Galleria"
    },
    "eventi": {
      "title": "Eventi & Spettacoli",
      "text": "Scopri i prossimi eventi, stage e spettacoli della Sirio Fit & Dance."
    },
    "contatti": {
      "title": "Contattaci",
      "button": "Invia Messaggio"
    }
  }'
);

INSERT INTO courses (slug, categoria, titolo, descrizione, immagine, disponibile, ordine, orari_testo, orari_json) VALUES
('funzionale', 'fitness', 'Funzionale', 'Allenamento a circuito per forza, resistenza e mobilità.', 'assets/img/corsi/funzionale.svg', 1, 0, 'Lunedì–Sabato
Più fasce giornaliere', '{"orari_settimana":[{"giorno":"Lunedì","fasce":["09:00","18:30"]},{"giorno":"Mercoledì","fasce":["18:30"]},{"giorno":"Venerdì","fasce":["19:00"]}]}'),
('ginnastica-corpo-libero', 'fitness', 'Ginnastica Corpo Libero', 'Esercizi di tonicità, equilibrio e postura in un percorso accessibile a tutti.', 'assets/img/corsi/ginnastica-corpo-libero.svg', 1, 1, 'Martedì e Giovedì · 18:00', '{"orari_settimana":[{"giorno":"Martedì","fasce":["18:00"]},{"giorno":"Giovedì","fasce":["18:00"]}]}'),
('ginnastica-dolce', 'fitness', 'Ginnastica Dolce', 'Lavoro dolce e mirato per migliorare posture, flessibilità e benessere.', 'assets/img/corsi/ginnastica-dolce.svg', 1, 2, 'Lunedì e Mercoledì · 10:30', '{"orari_settimana":[{"giorno":"Lunedì","fasce":["10:30"]},{"giorno":"Mercoledì","fasce":["10:30"]}]}'),
('pilates', 'fitness', 'Pilates', 'Programma di stretching e rafforzamento per il corpo e la mente.', 'assets/img/corsi/pilates.svg', 1, 3, 'Martedì e Venerdì · 17:30', '{"orari_settimana":[{"giorno":"Martedì","fasce":["17:30"]},{"giorno":"Venerdì","fasce":["17:30"]}]}'),
('total-body', 'fitness', 'Total Body', 'Allenamento completo per forza, resistenza e postura.', 'assets/img/corsi/total-body.svg', 1, 4, 'Lunedì e Giovedì · 19:00', '{"orari_settimana":[{"giorno":"Lunedì","fasce":["19:00"]},{"giorno":"Giovedì","fasce":["19:00"]}]}'),
('stretching', 'fitness', 'Stretching', 'Sessioni dedicate a mobilità, rilassamento e recupero.', 'assets/img/corsi/stretching.svg', 1, 5, 'Mercoledì · 20:00', '{"orari_settimana":[{"giorno":"Mercoledì","fasce":["20:00"]}]}'),
('gag', 'fitness', 'GAG', 'Workout dinamico per migliorare coordinazione e tono muscolare.', 'assets/img/corsi/gag.svg', 1, 6, 'Giovedì · 18:30', '{"orari_settimana":[{"giorno":"Giovedì","fasce":["18:30"]}]}'),
('predanza', 'danza', 'Pre-danza', 'Percorso introduttivo per i più piccoli che vogliono avvicinarsi alla danza.', 'assets/img/corsi/predanza.svg', 1, 7, 'Sabato · 10:00', '{"orari_settimana":[{"giorno":"Sabato","fasce":["10:00"]}]}'),
('danza', 'danza', 'Danza', 'Tecnica e coreografie per crescere nella danza classica e contemporanea.', 'assets/img/corsi/danza.svg', 1, 8, 'Lunedì e Mercoledì · 17:15', '{"orari_settimana":[{"giorno":"Lunedì","fasce":["17:15"]},{"giorno":"Mercoledì","fasce":["17:15"]}]}'),
('caraibico', 'danza', 'Caraibico', 'Energia, ritmo e movimento con lezioni vivaci e coinvolgenti.', 'assets/img/corsi/caraibico.svg', 1, 9, 'Martedì · 20:00', '{"orari_settimana":[{"giorno":"Martedì","fasce":["20:00"]}]}'),
('hip-hop', 'danza', 'Hip Hop', 'Tecnica, ritmo e stile in un percorso moderno e coinvolgente.', 'assets/img/corsi/hip-hop.svg', 1, 10, 'Giovedì · 20:30', '{"orari_settimana":[{"giorno":"Giovedì","fasce":["20:30"]}]}'),
('pole-dance', 'danza', 'Pole Dance', 'Lavoro di forza, fluidità e postura con un approccio tecnico.', 'assets/img/corsi/pole-dance.svg', 1, 11, 'Venerdì · 20:00', '{"orari_settimana":[{"giorno":"Venerdì","fasce":["20:00"]}]}'),
('judo', 'sport', 'Judo', 'Disciplina marziale per bambini e ragazzi.', 'assets/img/corsi/judo.svg', 1, 12, 'Lunedì e Mercoledì', '{"orari_settimana":[{"giorno":"Lunedì","fasce":["17:30"]},{"giorno":"Mercoledì","fasce":["17:30"]}]}');

INSERT INTO events (title, date, description, location) VALUES
('Saggio di fine anno', '2026-06-20', 'Spettacolo aperto al pubblico con coreografie e performance.', 'Sirio Fit & Dance — Via A. Pertini, 16, Alcamo (TP)');
