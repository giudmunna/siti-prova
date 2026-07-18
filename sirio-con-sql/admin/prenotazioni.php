<?php
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/../includes/db.php';
admin_require_login();

$conn = db_connect();
$reservations = [];
if ($conn !== null) {
    $result = $conn->query('SELECT r.*, c.titolo AS corso_titolo FROM reservations r LEFT JOIN courses c ON c.id = r.corso_id ORDER BY r.created_at DESC');
    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $reservations[] = $row;
        }
    }
}

$pageTitle = 'Prenotazioni';
$activePage = 'prenotazioni';
require __DIR__ . '/includes/layout-top.php';
?>

<div class="admin-card">
  <h1>Prenotazioni ricevute</h1>
  <p class="lead">Qui trovi tutte le richieste di prova salvate nel database locale.</p>

  <?php if (empty($reservations)): ?>
    <p class="admin-empty">Nessuna prenotazione ancora.</p>
  <?php else: ?>
    <ul class="event-list">
      <?php foreach ($reservations as $reservation): ?>
        <li>
          <h3><?= htmlspecialchars($reservation['nome']) ?></h3>
          <p class="meta">
            <strong>Telefono:</strong> <?= htmlspecialchars($reservation['telefono'] ?: '—') ?>
            · <strong>Corso:</strong> <?= htmlspecialchars($reservation['corso_titolo'] ?: '—') ?>
          </p>
          <p class="desc"><?= nl2br(htmlspecialchars($reservation['messaggio'])) ?></p>
          <p class="meta"><strong>Ricevuta:</strong> <?= htmlspecialchars($reservation['created_at']) ?></p>
        </li>
      <?php endforeach; ?>
    </ul>
  <?php endif; ?>
</div>

<?php require __DIR__ . '/includes/layout-bottom.php'; ?>