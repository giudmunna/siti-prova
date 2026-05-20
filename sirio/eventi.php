<?php require 'header.php'; 

$eventsFile = 'admin/events.json';
$events = file_exists($eventsFile) ? json_decode(file_get_contents($eventsFile), true) : [];
?>

<section class="section">
  <div class="container">
    <h2><?= $content['eventi']['title'] ?></h2>
    <p class="lead"><?= $content['eventi']['text'] ?></p>
  
    <div style="margin-top: 28px;">
      <?php if (empty($events)): ?>
        <div class="event-card">
          <h3>In aggiornamento</h3>
          <p>Nessun evento programmato al momento. Seguici o scrivici per ricevere le prossime date.</p>
          <div style="margin-top: 14px;">
            <a class="btn" href="contatti.php">Richiedi info</a>
          </div>
        </div>
      <?php else: ?>
        <?php foreach($events as $event): ?>
          <div class="event-card">
            <h3><?= htmlspecialchars($event['title']) ?></h3>
            <p><strong>Data:</strong> <?= date('d/m/Y', strtotime($event['date'])) ?></p>
            <p><?= htmlspecialchars($event['description']) ?></p>
            <p><strong>Luogo:</strong> <?= htmlspecialchars($event['location']) ?></p>
          </div>
        <?php endforeach; ?>
      <?php endif; ?>
    </div>
  </div>
</section>

<?php require 'footer.php'; ?>