<?php require 'header.php'; ?>

<section class="section">
  <h2><?= $content['corsi']['title'] ?></h2>
  
  <div class="courses-grid">
    <?php foreach($content['corsi']['lista'] as $corso): ?>
      <div class="course-card">
        <h3><?= $corso['titolo'] ?></h3>
        <p><?= $corso['descrizione'] ?></p>
      </div>
    <?php endforeach; ?>
  </div>
</section>

<?php require 'footer.php'; ?>