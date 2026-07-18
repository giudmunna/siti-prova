<?php
$bodyClass = 'page-contatti';
require 'header.php';
$corsoSlug = trim($_GET['corso'] ?? '');
$corsoSelezionato = $corsoSlug !== '' ? getCorsoBySlug($content, $corsoSlug) : null;
?>
<section class="section">
  <div class="container">
    <h2><?= $content['contatti']['title'] ?></h2>
    <p class="lead">Per una prova o per richiedere informazioni sul corso migliore, usa la pagina di prenotazione dedicata.</p>

    <div class="contact-grid" style="margin-top: 32px;">
      <div class="contact-info">
        <p class="contact-legal"><strong><?= htmlspecialchars($content['site']['legal_name']) ?></strong></p>
        <p><strong><i class="fa-solid fa-location-dot"></i></strong> <?= htmlspecialchars($content['site']['address']) ?></p>
        <p>
          <strong><i class="fa-solid fa-phone"></i></strong>
          <a href="tel:<?= phoneTel($content['site']['phone_mobile']) ?>"><?= htmlspecialchars($content['site']['phone_mobile']) ?></a>
          <span class="contact-sep">/</span>
          <a href="tel:<?= phoneTel($content['site']['phone_landline']) ?>"><?= htmlspecialchars($content['site']['phone_landline']) ?></a>
        </p>
        <p><strong><i class="fa-solid fa-envelope"></i></strong> <a href="mailto:<?= htmlspecialchars($content['site']['email']) ?>"><?= htmlspecialchars($content['site']['email']) ?></a></p>
        <p><strong><i class="fa-solid fa-id-card"></i></strong> C.F. e P. IVA: <?= htmlspecialchars($content['site']['vat']) ?></p>
        <p class="mini"><strong>Nota:</strong> il form di prenotazione salva i dati nel database locale di XAMPP, così puoi provarlo anche con un hosting gratuito.</p>
      </div>

      <div class="contact-form">
        <?php if ($corsoSelezionato): ?>
          <p class="lead" style="text-align:left; margin-bottom: 16px;">Corso selezionato: <strong><?= htmlspecialchars($corsoSelezionato['titolo']) ?></strong></p>
        <?php endif; ?>
        <a class="btn" href="prenota.php<?= $corsoSelezionato ? '?corso=' . urlencode($corsoSelezionato['slug']) : '' ?>">Prenota una prova</a>
      </div>
    </div>
  </div>
</section>

<?php require 'footer.php'; ?>
