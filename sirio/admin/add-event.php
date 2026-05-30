<?php
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/events.php';

admin_require_login();

$errors = [];
if (!admin_events_is_writable()) {
    $errors[] = 'Il file degli eventi non è scrivibile. Contatta il tecnico o imposta i permessi su admin/events.json.';
}
$form = [
    'title' => '',
    'date' => '',
    'description' => '',
    'location' => 'Sirio Fit & Dance — Via A. Pertini, 16, Alcamo (TP)',
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $result = admin_validate_event_input($_POST);
    $errors = $result['errors'];
    $form = $result['data'];

    if (empty($errors)) {
        if (admin_add_event($form)) {
            header('Location: dashboard.php?saved=1');
            exit;
        }
        $errors[] = 'Impossibile salvare l\'evento. Verifica i permessi della cartella admin.';
    }
}

$pageTitle = 'Aggiungi evento';
$activePage = 'add-event';
require __DIR__ . '/includes/layout-top.php';
?>

<div class="admin-card">
  <h1>Aggiungi nuovo evento</h1>
  <p class="lead">L'evento comparirà subito nella pagina Eventi del sito.</p>

  <?php if (!empty($errors)): ?>
    <div class="admin-alert error" role="alert">
      <strong>Correggi i seguenti errori:</strong>
      <ul>
        <?php foreach ($errors as $err): ?>
          <li><?= htmlspecialchars($err) ?></li>
        <?php endforeach; ?>
      </ul>
    </div>
  <?php endif; ?>

  <form class="admin-form" method="POST" action="add-event.php" novalidate>
    <div class="field">
      <label for="title">Titolo evento *</label>
      <input type="text" id="title" name="title" value="<?= htmlspecialchars($form['title']) ?>" placeholder="Es. Saggio di fine anno" required maxlength="200">
    </div>

    <div class="field">
      <label for="date">Data *</label>
      <input type="date" id="date" name="date" value="<?= htmlspecialchars($form['date']) ?>" required>
    </div>

    <div class="field">
      <label for="description">Descrizione *</label>
      <textarea id="description" name="description" placeholder="Descrivi l'evento, orari, costi..." required><?= htmlspecialchars($form['description']) ?></textarea>
    </div>

    <div class="field">
      <label for="location">Luogo *</label>
      <input type="text" id="location" name="location" value="<?= htmlspecialchars($form['location']) ?>" placeholder="Indirizzo o sala" required>
    </div>

    <div class="admin-actions">
      <button type="submit" class="btn-admin">Salva evento</button>
      <a class="btn-admin secondary" href="dashboard.php">Annulla</a>
    </div>
  </form>
</div>

<?php require __DIR__ . '/includes/layout-bottom.php'; ?>
