<?php require 'header.php'; ?>

<section class="section">
  <h2><?= $content['contatti']['title'] ?></h2>
  
  <div class="contact-grid">
    <div class="contact-info">
      <p><strong>📍</strong> <?= $content['site']['address'] ?></p>
      <p><strong>📞</strong> <a href="tel:<?= str_replace(' ', '', $content['site']['phone']) ?>"><?= $content['site']['phone'] ?></a></p>
      <p><strong>✉️</strong> <a href="mailto:<?= $content['site']['email'] ?>"><?= $content['site']['email'] ?></a></p>
    </div>

    <form action="invia.php" method="POST" class="contact-form">
      <input type="text" name="nome" placeholder="Nome e Cognome" required>
      <input type="email" name="email" placeholder="La tua email" required>
      <input type="text" name="oggetto" placeholder="Oggetto" required>
      <textarea name="messaggio" placeholder="Scrivi il tuo messaggio..." rows="7" required></textarea>
      <button type="submit" class="btn"><?= $content['contatti']['button'] ?></button>
    </form>
  </div>
</section>

<?php require 'footer.php'; ?>