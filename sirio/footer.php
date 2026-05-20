</main>

<footer>
  <div class="footer-inner">
    <div class="footer-top">
      <div class="footer-brand">SIRIO</div>
      <div class="footer-links">
        <a href="contatti.php"><i class="fa-solid fa-location-dot"></i> <?= $content['site']['address'] ?></a>
        <a href="tel:<?= str_replace(' ', '', $content['site']['phone']) ?>"><i class="fa-solid fa-phone"></i> <?= $content['site']['phone'] ?></a>
        <a href="mailto:<?= $content['site']['email'] ?>"><i class="fa-solid fa-envelope"></i> <?= $content['site']['email'] ?></a>
      </div>
    </div>
    <div class="footer-bottom">
      &copy; <?= date("Y") ?> <?= $content['site']['name'] ?> — <?= $content['site']['tagline'] ?>
    </div>
  </div>
</footer>

<script src="assets/js/script.js"></script>
</body>
</html>