<?php
require 'header.php';
$corsoSlug = trim($_GET['corso'] ?? '');
$corsoSelezionato = $corsoSlug !== '' ? getCorsoBySlug($content, $corsoSlug) : null;
$oggettoDefault = $corsoSelezionato ? 'Info corso: ' . $corsoSelezionato['titolo'] : '';
?>
<section class="section">
  <div class="container">
    <h2><?= $content['contatti']['title'] ?></h2>
    <p class="lead">Siamo felici di aiutarti a scegliere il corso migliore. Scrivici: rispondiamo il prima possibile.</p>
    
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
        <p class="mini"><strong>Tip:</strong> indica il corso che ti interessa e il tuo livello (principiante/intermedio/avanzato).</p>
      </div>

      <form action="invia.php" method="POST" class="contact-form">
        <input type="text" name="nome" placeholder="Nome e Cognome" required>
        <input type="email" name="email" placeholder="La tua email" required>
        <input type="text" name="oggetto" placeholder="Oggetto" value="<?= htmlspecialchars($oggettoDefault) ?>" required>
        <textarea name="messaggio" placeholder="Scrivi il tuo messaggio..." rows="7" required></textarea>
        <button type="submit" class="btn"><?= $content['contatti']['button'] ?></button>
      </form>
    </div>
  </div>
</section>

<?php require 'footer.php'; ?>
