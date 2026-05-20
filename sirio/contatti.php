<?php require 'header.php'; ?>

<section class="section">
  <div class="container">
    <h2><?= $content['contatti']['title'] ?></h2>
    <p class="lead">Siamo felici di aiutarti a scegliere il corso migliore. Scrivici: rispondiamo il prima possibile.</p>
    
    <div class="contact-grid" style="margin-top: 32px;">
      <div class="contact-info">
        <p><strong><i class="fa-solid fa-location-dot"></i></strong> <?= $content['site']['address'] ?></p>
        <p><strong><i class="fa-solid fa-phone"></i></strong> <a href="tel:<?= str_replace(' ', '', $content['site']['phone']) ?>"><?= $content['site']['phone'] ?></a></p>
        <p><strong><i class="fa-solid fa-envelope"></i></strong> <a href="mailto:<?= $content['site']['email'] ?>"><?= $content['site']['email'] ?></a></p>
        <p class="mini"><strong>Tip:</strong> indica il corso che ti interessa e il tuo livello (principiante/intermedio/avanzato).</p>
      </div>

      <form action="invia.php" method="POST" class="contact-form">
        <input type="text" name="nome" placeholder="Nome e Cognome" required>
        <input type="email" name="email" placeholder="La tua email" required>
        <input type="text" name="oggetto" placeholder="Oggetto" required>
        <textarea name="messaggio" placeholder="Scrivi il tuo messaggio..." rows="7" required></textarea>
        <button type="submit" class="btn"><?= $content['contatti']['button'] ?></button>
      </form>
    </div>
  </div>
</section>

<?php require 'footer.php'; ?>