<?php
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/events.php';

admin_require_login();

$events = admin_load_events();
$saved = isset($_GET['saved']) && $_GET['saved'] === '1';

$pageTitle = 'Dashboard';
$activePage = 'dashboard';
require __DIR__ . '/includes/layout-top.php';
?>

<div class="admin-card">
  <h1>Pannello amministratore</h1>
  <p class="lead">Gestisci gli eventi pubblicati su Sirio Fit & Dance.</p>

  <?php if ($saved): ?>
    <div class="admin-alert success" role="status">Evento salvato con successo.</div>
  <?php endif; ?>

  <?php if (!admin_events_is_writable()): ?>
    <div class="admin-alert error" role="alert">
      Attenzione: non è possibile scrivere su <code>admin/events.json</code>. Imposta i permessi di scrittura per il server web.
    </div>
  <?php endif; ?>

  <div class="admin-actions">
    <a class="btn-admin" href="add-event.php">➕ Aggiungi nuovo evento</a>
    <a class="btn-admin secondary" href="../eventi.php" target="_blank" rel="noopener">Visualizza pagina eventi</a>
  </div>

  <h2 style="margin-top: 32px; font-size: 1.2rem;">Eventi in programma (<?= count($events) ?>)</h2>

  <?php if (empty($events)): ?>
    <p class="admin-empty">Nessun evento ancora. Clicca su «Aggiungi nuovo evento» per inserire il primo.</p>
  <?php else: ?>
    <ul class="event-list">
      <?php foreach ($events as $event): ?>
        <li>
          <h3><?= htmlspecialchars($event['title'] ?? '') ?></h3>
          <p class="meta">
            <strong>Data:</strong> <?= htmlspecialchars(admin_format_event_date($event['date'] ?? '')) ?>
            · <strong>Luogo:</strong> <?= htmlspecialchars($event['location'] ?? '') ?>
          </p>
          <p class="desc"><?= nl2br(htmlspecialchars($event['description'] ?? '')) ?></p>
        </li>
      <?php endforeach; ?>
    </ul>
  <?php endif; ?>
</div>

<?php require __DIR__ . '/includes/layout-bottom.php'; ?>
