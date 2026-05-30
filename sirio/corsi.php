<?php
$bodyClass = 'page-corsi';
require 'header.php';
?>

<section class="section">
  <div class="container">
    <h2><?= $content['corsi']['title'] ?></h2>
    <p class="lead">Scegli il tuo corso: possiamo consigliarti il livello più adatto e una prova in sede.</p>

    <div class="courses-grid courses-grid--no-images" style="margin-top: 34px;">
      <?php $noCardImage = true; foreach ($content['corsi']['lista'] as $corso): ?>
        <?php include __DIR__ . '/includes/course-card.php'; ?>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<?php require 'footer.php'; ?>
