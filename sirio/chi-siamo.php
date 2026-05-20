<?php require 'header.php'; ?>

<section class="section">
  <div class="container">
    <h2><?= $content['chi_siamo']['title'] ?></h2>
    <p class="lead"><?= $content['chi_siamo']['text'] ?></p>

    <div class="two-col">
      <div>
        <div class="feature-list">
          <div class="feature">
            <i class="fa-solid fa-calendar-check"></i>
            <div>
              <strong>Lezioni tutto l’anno</strong>
              <span>Percorsi continuativi e programmi stagionali.</span>
            </div>
          </div>
          <div class="feature">
            <i class="fa-solid fa-child"></i>
            <div>
              <strong>Corsi per tutte le età</strong>
              <span>Da bambini ad adulti, in base agli obiettivi.</span>
            </div>
          </div>
          <div class="feature">
            <i class="fa-solid fa-person-chalkboard"></i>
            <div>
              <strong>Insegnanti qualificati</strong>
              <span>Metodo, tecnica e attenzione alla persona.</span>
            </div>
          </div>
          <div class="feature">
            <i class="fa-solid fa-face-smile"></i>
            <div>
              <strong>Ambiente motivante</strong>
              <span>Un clima positivo che fa venire voglia di tornare.</span>
            </div>
          </div>
        </div>

        <div style="margin-top: 22px;">
          <a class="btn" href="contatti.php">Contattaci</a>
        </div>
      </div>

      <img src="assets/img/about.svg" alt="Sirio Fit & Dance - Chi siamo">
    </div>
  </div>
</section>

<?php require 'footer.php'; ?>