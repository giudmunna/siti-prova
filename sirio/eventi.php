<?php require 'header.php'; 

$eventsFile = 'admin/events.json';
$events = file_exists($eventsFile) ? json_decode(file_get_contents($eventsFile), true) : [];
?>

<section class="section">
  <h2><?= $content['eventi']['title'] ?></h2>
  
  <?php if (empty($events)): ?>
    <p>Nessun evento programmato al momento.</p>
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
</section>

<?php require 'footer.php'; ?>