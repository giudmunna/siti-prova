<?php require 'header.php'; ?>

<section class="section">
  <div class="container">
    <h2><?= $content['galleria']['title'] ?></h2>
    <p class="lead">Alcuni momenti in sala, lezioni e spettacoli. Puoi aggiungere le tue immagini in <strong>assets/img/galleria/</strong>.</p>

    <div class="gallery-grid" id="gallery-grid" style="margin-top: 30px;">
      <?php
        $files = glob('assets/img/galleria/*.{jpg,jpeg,png,webp,gif,svg}', GLOB_BRACE) ?: [];
        sort($files);
      ?>

      <?php if (empty($files)): ?>
        <div class="gallery-empty">
          Nessuna foto trovata. Carica immagini in <strong>assets/img/galleria/</strong> (jpg/png/webp/svg) e ricarica la pagina.
        </div>
      <?php else: ?>
        <?php foreach($files as $img): ?>
          <img
            src="<?= htmlspecialchars($img) ?>"
            alt="Galleria Sirio"
            loading="lazy"
            class="js-lightbox"
            data-lightbox-src="<?= htmlspecialchars($img) ?>"
          >
        <?php endforeach; ?>
      <?php endif; ?>
    </div>
  </div>
</section>

<?php require 'footer.php'; ?>