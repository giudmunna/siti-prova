<?php
/** @var array $corso */
$disponibile = !empty($corso['disponibile']);
$url = corsoUrl($corso);
$showImage = empty($noCardImage);
$img = $corso['immagine'] ?? 'assets/img/corsi/placeholder.svg';
$ctaLabel = $disponibile ? 'Info e orari' : 'Prossimamente';
?>

<?php if ($showImage): ?>
<a class="course-card course-card-link" href="<?= htmlspecialchars($url) ?>">
  <div class="course-card-media">
    <img src="<?= htmlspecialchars($img) ?>" alt="<?= htmlspecialchars($corso['titolo']) ?>" loading="lazy">
  </div>
  <div class="course-card-body">
    <h3><?= htmlspecialchars($corso['titolo']) ?></h3>
    <p><?= htmlspecialchars($corso['descrizione']) ?></p>
    <span class="course-card-cta"><?= $ctaLabel ?></span>
  </div>
</a>
<?php else: ?>
<div class="course-card course-card--no-image">
  <div class="course-card-body">
    <h3><?= htmlspecialchars($corso['titolo']) ?></h3>
    <p><?= htmlspecialchars($corso['descrizione']) ?></p>
    <?php if ($disponibile): ?>
      <a class="course-card-cta" href="<?= htmlspecialchars($url) ?>"><?= $ctaLabel ?></a>
    <?php else: ?>
      <span class="course-card-cta course-card-cta--disabled"><?= $ctaLabel ?></span>
    <?php endif; ?>
  </div>
</div>
<?php endif; ?>
