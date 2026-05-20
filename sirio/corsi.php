<?php require 'header.php'; ?>

<section class="section">
  <div class="container">
    <h2><?= $content['corsi']['title'] ?></h2>
    <p class="lead">Scegli il tuo corso: possiamo consigliarti il livello più adatto e una prova in sede.</p>

    <div class="courses-grid" style="margin-top: 34px;">
      <?php foreach($content['corsi']['lista'] as $corso): ?>
        <div class="course-card">
          <h3><?= $corso['titolo'] ?></h3>
          <p><?= $corso['descrizione'] ?></p>
          <div style="margin-top: 14px;">
            <a class="btn outline" style="padding: 12px 18px; border-radius: 999px;" href="contatti.php">Chiedi info</a>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<?php require 'footer.php'; ?>