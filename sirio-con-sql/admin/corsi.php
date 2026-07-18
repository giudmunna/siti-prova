<?php
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/../includes/db.php';
admin_require_login();

$conn = db_connect();
if ($conn === null) {
    $error = 'Impossibile connettersi al database MySQL.';
} else {
    $error = '';
}

$editingCourse = null;
$formValues = [
    'slug' => '',
    'titolo' => '',
    'categoria' => 'fitness',
    'immagine' => 'assets/img/corsi/placeholder.svg',
    'descrizione' => '',
    'orari_testo' => '',
    'schedule_rows' => [],
    'disponibile' => 1,
    'ordine' => 0,
    'course_id' => 0,
];

function normalizeScheduleRowsFromJson(?string $json): array
{
    if ($json === null || trim($json) === '') {
        return [];
    }

    $decoded = json_decode($json, true);
    if (!is_array($decoded) || empty($decoded['orari_settimana']) || !is_array($decoded['orari_settimana'])) {
        return [];
    }

    $rows = [];
    foreach ($decoded['orari_settimana'] as $row) {
        if (!is_array($row)) {
            continue;
        }

        $giorno = trim((string) ($row['giorno'] ?? ''));
        $fasce = [];
        if (!empty($row['fasce']) && is_array($row['fasce'])) {
            foreach ($row['fasce'] as $fascia) {
                $fasciaText = trim((string) $fascia);
                if ($fasciaText !== '') {
                    $fasce[] = $fasciaText;
                }
            }
        }

        if ($giorno !== '' || !empty($fasce)) {
            $rows[] = ['giorno' => $giorno, 'fasce' => $fasce];
        }
    }

    return $rows;
}

function parseScheduleRowsFromPost(array $post): array
{
    if (empty($post['schedule']) || !is_array($post['schedule'])) {
        return [];
    }

    $rows = [];
    foreach ($post['schedule'] as $row) {
        if (!is_array($row)) {
            continue;
        }

        $giorno = trim((string) ($row['giorno'] ?? ''));
        $fasceText = trim((string) ($row['fasce'] ?? ''));
        $fasce = [];
        if ($fasceText !== '') {
            $parts = preg_split('/\r\n|[;,]+/', $fasceText);
            if (is_array($parts)) {
                foreach ($parts as $part) {
                    $partText = trim((string) $part);
                    if ($partText !== '') {
                        $fasce[] = $partText;
                    }
                }
            }
        }

        if ($giorno !== '' || !empty($fasce)) {
            $rows[] = ['giorno' => $giorno, 'fasce' => $fasce];
        }
    }

    return $rows;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? 'save';
    $courseId = isset($_POST['course_id']) ? (int) $_POST['course_id'] : 0;
    if ($action === 'edit' && $courseId > 0) {
        $stmt = $conn->prepare('SELECT * FROM courses WHERE id = ? LIMIT 1');
        $stmt->bind_param('i', $courseId);
        $stmt->execute();
        $editingCourse = $stmt->get_result()->fetch_assoc();
        $stmt->close();
        if ($editingCourse) {
                $formValues = [
                'slug' => $editingCourse['slug'] ?? '',
                'titolo' => $editingCourse['titolo'] ?? '',
                'categoria' => $editingCourse['categoria'] ?? 'fitness',
                'immagine' => $editingCourse['immagine'] ?? 'assets/img/corsi/placeholder.svg',
                'descrizione' => $editingCourse['descrizione'] ?? '',
                'orari_testo' => $editingCourse['orari_testo'] ?? '',
                'schedule_rows' => normalizeScheduleRowsFromJson($editingCourse['orari_json'] ?? null),
                'disponibile' => (int) ($editingCourse['disponibile'] ?? 1),
                'ordine' => (int) ($editingCourse['ordine'] ?? 0),
                'course_id' => (int) $editingCourse['id'],
            ];
        }
        $saved = false;
        $error = '';
    } elseif ($action === 'delete' && $courseId > 0) {
        $stmt = $conn->prepare('DELETE FROM courses WHERE id = ?');
        $stmt->bind_param('i', $courseId);
        $stmt->execute();
        $stmt->close();
        $saved = true;
        $error = '';
    } elseif ($action === 'toggle' && $courseId > 0) {
        $stmt = $conn->prepare('UPDATE courses SET disponibile = IF(disponibile = 1, 0, 1) WHERE id = ?');
        $stmt->bind_param('i', $courseId);
        $stmt->execute();
        $stmt->close();
        $saved = true;
        $error = '';
    } else {
        $slug = strtolower(trim((string) ($_POST['slug'] ?? '')));
        $titolo = trim((string) ($_POST['titolo'] ?? ''));
        $categoria = trim((string) ($_POST['categoria'] ?? 'sport'));
        $descrizione = trim((string) ($_POST['descrizione'] ?? ''));
        $immagine = trim((string) ($_POST['immagine'] ?? 'assets/img/corsi/placeholder.svg'));
        $disponibile = isset($_POST['disponibile']) ? 1 : 0;
        $ordine = isset($_POST['ordine']) ? (int) $_POST['ordine'] : 0;
        $orari_testo = trim((string) ($_POST['orari_testo'] ?? ''));
        $scheduleRows = parseScheduleRowsFromPost($_POST);
        $orari_json = '';
        if (!empty($scheduleRows)) {
            $orari_json = json_encode(['orari_settimana' => $scheduleRows], JSON_UNESCAPED_UNICODE);
        }

        if (!empty($_FILES['immagine_file']['name'])) {
            $ext = pathinfo($_FILES['immagine_file']['name'], PATHINFO_EXTENSION);
            $allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg'];
            if (!in_array(strtolower($ext), $allowed, true)) {
                $saved = false;
                $error = 'Formato immagine non supportato. Usa JPG, PNG, GIF, WEBP o SVG.';
            } else {
                $targetDir = __DIR__ . '/../assets/img/corsi';
                if (!is_dir($targetDir)) {
                    mkdir($targetDir, 0755, true);
                }
                $targetName = $slug !== '' ? $slug . '.' . $ext : 'course.' . $ext;
                $targetPath = $targetDir . '/' . $targetName;
                if (move_uploaded_file($_FILES['immagine_file']['tmp_name'], $targetPath)) {
                    $immagine = 'assets/img/corsi/' . $targetName;
                } else {
                    $saved = false;
                    $error = 'Non è stato possibile caricare l’immagine.';
                }
            }
        }

        if (empty($error) && $slug !== '' && $titolo !== '' && $descrizione !== '') {
            if ($courseId > 0) {
                $stmt = $conn->prepare('UPDATE courses SET slug = ?, categoria = ?, titolo = ?, descrizione = ?, immagine = ?, disponibile = ?, ordine = ?, orari_testo = ?, orari_json = ? WHERE id = ?');
                $stmt->bind_param('sssssiissi', $slug, $categoria, $titolo, $descrizione, $immagine, $disponibile, $ordine, $orari_testo, $orari_json, $courseId);
            } else {
                $stmt = $conn->prepare('INSERT INTO courses (slug, categoria, titolo, descrizione, immagine, disponibile, ordine, orari_testo, orari_json) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)');
                $stmt->bind_param('sssssiiss', $slug, $categoria, $titolo, $descrizione, $immagine, $disponibile, $ordine, $orari_testo, $orari_json);
            }
            $stmt->execute();
            $stmt->close();
            $saved = true;
            $formValues = [
                'slug' => $slug,
                'titolo' => $titolo,
                'categoria' => $categoria,
                'immagine' => $immagine,
                'descrizione' => $descrizione,
                'orari_testo' => $orari_testo,
                'schedule_rows' => $scheduleRows,
                'disponibile' => $disponibile,
                'ordine' => $ordine,
                'course_id' => $courseId,
            ];
        } elseif (empty($error)) {
            $saved = false;
            $error = 'Slug, titolo e descrizione sono obbligatori.';
        }
    }
}

$corsi = [];
if ($conn !== null) {
    $result = $conn->query('SELECT * FROM courses ORDER BY ordine, titolo');
    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $corsi[] = $row;
        }
    }
}

$pageTitle = 'Gestione corsi';
$activePage = 'corsi';
require __DIR__ . '/includes/layout-top.php';
?>

<div class="admin-card">
  <h1>Gestione corsi e immagini</h1>
  <p class="lead">Aggiungi o aggiorna i corsi del sito. Le immagini vanno caricate nella cartella assets/img/corsi e poi indicate qui.</p>

  <?php if (!empty($error)): ?>
    <div class="admin-alert error" role="alert"><?= htmlspecialchars($error) ?></div>
  <?php endif; ?>

  <?php if (!empty($saved)): ?>
    <div class="admin-alert success" role="status">Corso salvato con successo.</div>
  <?php endif; ?>

  <form class="admin-form" method="POST" enctype="multipart/form-data">
    <input type="hidden" name="action" value="save">
    <input type="hidden" name="course_id" value="<?= (int) ($formValues['course_id'] ?? 0) ?>">
    <div class="field">
      <label for="slug">Slug</label>
      <input type="text" id="slug" name="slug" value="<?= htmlspecialchars($formValues['slug']) ?>" placeholder="es. pilates" required>
    </div>
    <div class="field">
      <label for="titolo">Titolo</label>
      <input type="text" id="titolo" name="titolo" value="<?= htmlspecialchars($formValues['titolo']) ?>" required>
    </div>
    <div class="field">
      <label for="categoria">Categoria</label>
      <input type="text" id="categoria" name="categoria" value="<?= htmlspecialchars($formValues['categoria']) ?>" placeholder="fitness, danza, sport">
    </div>
    <div class="field">
      <label for="ordine">Ordine</label>
      <input type="number" id="ordine" name="ordine" value="<?= (int) $formValues['ordine'] ?>" min="0">
    </div>
    <div class="field">
      <label for="immagine">Immagine (path)</label>
      <input type="text" id="immagine" name="immagine" value="<?= htmlspecialchars($formValues['immagine']) ?>" placeholder="assets/img/corsi/nome.svg">
    </div>
    <div class="field">
      <label for="immagine_file">Carica immagine dal computer</label>
      <input type="file" id="immagine_file" name="immagine_file" accept="image/*">
    </div>
    <div class="field">
      <label for="descrizione">Descrizione</label>
      <textarea id="descrizione" name="descrizione" required><?= htmlspecialchars($formValues['descrizione']) ?></textarea>
    </div>
    <div class="field">
      <label>Orari settimanali</label>
      <div class="schedule-editor">
        <table class="schedule-editor-table">
          <thead>
            <tr>
              <th>Giorno</th>
              <th>Orari</th>
              <th></th>
            </tr>
          </thead>
          <tbody id="schedule-rows">
            <?php if (!empty($formValues['schedule_rows'])): ?>
              <?php foreach ($formValues['schedule_rows'] as $index => $row): ?>
                <tr class="schedule-row">
                  <td><input type="text" name="schedule[<?= (int) $index ?>][giorno]" value="<?= htmlspecialchars($row['giorno'] ?? '') ?>" placeholder="es. Lunedì"></td>
                  <td><input type="text" name="schedule[<?= (int) $index ?>][fasce]" value="<?= htmlspecialchars(implode(', ', $row['fasce'] ?? [])) ?>" placeholder="09:00, 18:30"></td>
                  <td><button type="button" class="btn-admin secondary small remove-schedule-row">Rimuovi</button></td>
                </tr>
              <?php endforeach; ?>
            <?php else: ?>
              <tr class="schedule-row">
                <td><input type="text" name="schedule[0][giorno]" value="" placeholder="es. Lunedì"></td>
                <td><input type="text" name="schedule[0][fasce]" value="" placeholder="09:00, 18:30"></td>
                <td><button type="button" class="btn-admin secondary small remove-schedule-row">Rimuovi</button></td>
              </tr>
            <?php endif; ?>
          </tbody>
        </table>
        <button type="button" id="add-schedule-row" class="btn-admin secondary">Aggiungi giorno</button>
      </div>
    </div>
    <div class="field">
      <label for="orari_testo">Testo libero (opzionale)</label>
      <textarea id="orari_testo" name="orari_testo" placeholder="Inserisci una descrizione libera se vuoi..."><?= htmlspecialchars($formValues['orari_testo']) ?></textarea>
    </div>
    <div class="field">
      <label><input type="checkbox" name="disponibile" value="1" <?= !empty($formValues['disponibile']) ? 'checked' : '' ?>> Disponibile</label>
    </div>
    <button type="submit" class="btn-admin"><?= !empty($formValues['course_id']) ? 'Aggiorna corso' : 'Salva corso' ?></button>
  </form>

  <h2 style="margin-top: 32px; font-size: 1.15rem;">Corsi presenti</h2>
  <?php if (empty($corsi)): ?>
    <p class="admin-empty">Non è presente alcun corso nel database.</p>
  <?php else: ?>
    <ul class="event-list">
      <?php foreach ($corsi as $corso): ?>
        <li>
          <h3><?= htmlspecialchars($corso['titolo']) ?></h3>
          <p class="meta"><strong>Slug:</strong> <?= htmlspecialchars($corso['slug']) ?> · <strong>Categoria:</strong> <?= htmlspecialchars($corso['categoria']) ?> · <strong>Pubblico:</strong> <?= !empty($corso['disponibile']) ? 'Sì' : 'No' ?></p>
          <p class="desc"><?= nl2br(htmlspecialchars($corso['descrizione'])) ?></p>
          <?php if (!empty($corso['orari_testo'])): ?>
            <p class="desc"><strong>Orari:</strong> <?= nl2br(htmlspecialchars($corso['orari_testo'])) ?></p>
          <?php endif; ?>
          <div style="margin-top: 12px; display:flex; gap:10px; flex-wrap:wrap;">
            <form method="POST">
              <input type="hidden" name="action" value="edit">
              <input type="hidden" name="course_id" value="<?= (int) $corso['id'] ?>">
              <button type="submit" class="btn-admin secondary">Modifica</button>
            </form>
            <form method="POST">
              <input type="hidden" name="action" value="toggle">
              <input type="hidden" name="course_id" value="<?= (int) $corso['id'] ?>">
              <button type="submit" class="btn-admin secondary"><?= !empty($corso['disponibile']) ? 'Disattiva' : 'Attiva' ?></button>
            </form>
            <form method="POST">
              <input type="hidden" name="action" value="delete">
              <input type="hidden" name="course_id" value="<?= (int) $corso['id'] ?>">
              <button type="submit" class="btn-admin secondary">Elimina</button>
            </form>
          </div>
        </li>
      <?php endforeach; ?>
    </ul>
  <?php endif; ?>
</div>

<?php require __DIR__ . '/includes/layout-bottom.php'; ?>