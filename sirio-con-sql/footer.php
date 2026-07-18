</main>

<footer>
  <div class="footer-inner">
    <div class="footer-top">
      <div class="footer-brand">
        SIRIO
        <small><?= htmlspecialchars($content['site']['legal_name']) ?></small>
      </div>
      <div class="footer-links">
        <a href="contatti.php"><i class="fa-solid fa-location-dot"></i> <?= htmlspecialchars($content['site']['address']) ?></a>
        <a href="tel:<?= phoneTel($content['site']['phone_mobile']) ?>"><i class="fa-solid fa-mobile-screen"></i> <?= htmlspecialchars($content['site']['phone_mobile']) ?></a>
        <a href="tel:<?= phoneTel($content['site']['phone_landline']) ?>"><i class="fa-solid fa-phone"></i> <?= htmlspecialchars($content['site']['phone_landline']) ?></a>
        <a href="mailto:<?= htmlspecialchars($content['site']['email']) ?>"><i class="fa-solid fa-envelope"></i> <?= htmlspecialchars($content['site']['email']) ?></a>
        <span class="footer-vat"><i class="fa-solid fa-id-card"></i> P. IVA <?= htmlspecialchars($content['site']['vat']) ?></span>
      </div>
    </div>
    <div class="footer-bottom">
      &copy; <?= date("Y") ?> <?= htmlspecialchars($content['site']['name']) ?> — <?= htmlspecialchars($content['site']['tagline']) ?>
    </div>
  </div>
</footer>

<script src="assets/js/script.js"></script>
</body>
</html>
