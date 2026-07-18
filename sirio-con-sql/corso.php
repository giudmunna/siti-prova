<?php
require_once 'includes/functions.php';
require_once 'config.php';

$slug = trim($_GET['slug'] ?? '');
$corso = $slug !== '' ? getCorsoBySlug($content, $slug) : null;
$bodyClass = 'page-corso';

if (!$corso) {
    http_response_code(404);
}

require 'header.php';
?>

<section class="section">
  <div class="container">
    <?php if (!$corso): ?>
      <h2>Corso non trovato</h2>
      <p class="lead">Il corso che cerchi non esiste o non è più disponibile.</p>
      <a class="btn" href="corsi.php" style="margin-top: 24px;">Torna ai corsi</a>
    <?php else:
      $img = $corso['immagine'] ?? 'assets/img/corsi/placeholder.svg';
      $disponibile = !empty($corso['disponibile']);
    ?>
      <a class="back-link" href="corsi.php"><i class="fa-solid fa-arrow-left"></i> Tutti i corsi</a>

      <article class="course-detail">
        <div class="course-detail-photo">
          <img src="<?= htmlspecialchars($img) ?>" alt="<?= htmlspecialchars($corso['titolo']) ?>">
        </div>

        <div class="course-detail-main">
          <h1><?= htmlspecialchars($corso['titolo']) ?></h1>
          <p class="course-detail-desc"><?= htmlspecialchars($corso['descrizione']) ?></p>

          <?php if ($disponibile): ?>
            <p class="course-detail-status available">Iscrizioni aperte</p>
          <?php else: ?>
            <p class="course-detail-status unavailable">Non ancora disponibile</p>
          <?php endif; ?>

          <div class="course-detail-schedule">
            <h2>Orari</h2>
            <?php include __DIR__ . '/includes/schedule.php'; ?>
            <?php if (!$disponibile): ?>
              <p class="course-detail-note">Contattaci per essere avvisato all’apertura delle iscrizioni.</p>
            <?php endif; ?>
          </div>

          <div class="course-detail-actions">
            <a class="btn" href="contatti.php?corso=<?= urlencode($corso['slug']) ?>">Richiedi informazioni</a>
            <a class="btn outline" href="prenota.php?corso=<?= urlencode($corso['slug']) ?>">Prenota una prova</a>
          </div>
        </div>
      </article>
    <?php endif; ?>
  </div>
</section>

<?php require 'footer.php'; ?>
