<?php require 'header.php'; ?>

<section class="hero">
  <div class="hero-content">
    <h1><?= $content['home']['hero_title'] ?></h1>
    <p class="subtitle"><?= $content['home']['hero_subtitle'] ?></p>
    <div class="hero-actions">
      <a href="corsi.php" class="btn"><?= $content['home']['hero_button'] ?></a>
      <a href="contatti.php" class="btn secondary">Prenota una prova</a>
    </div>
    <div class="hero-badges" aria-label="Punti di forza">
      <span class="badge"><i class="fa-solid fa-dumbbell"></i> Fitness</span>
      <span class="badge"><i class="fa-solid fa-music"></i> Danza</span>
      <span class="badge"><i class="fa-solid fa-star"></i> Istruttori qualificati</span>
    </div>
  </div>
</section>

<section class="section">
  <div class="container">
    <h2>Servizi e corsi</h2>
    <p class="lead">Un’unica sede ad Alcamo per allenarti, divertirti e migliorare con percorsi per tutte le età e tutti i livelli.</p>

    <div class="courses-grid" style="margin-top: 34px;">
      <?php $homeCourses = getAllCourses(); if (empty($homeCourses)) { $homeCourses = $content['corsi']['lista'] ?? []; } foreach ($homeCourses as $corso): ?>
        <?php include __DIR__ . '/includes/course-card.php'; ?>
      <?php endforeach; ?>
    </div>

    <div style="margin-top: 34px;">
      <a class="btn" href="corsi.php">Vedi tutti i corsi</a>
    </div>
  </div>
</section>

<section class="section alt">
  <div class="container">
    <h2>Perché scegliere Sirio</h2>
    <p class="lead"><?= $content['chi_siamo']['text'] ?></p>

    <div class="two-col">
      <div>
        <div class="feature-list">
          <div class="feature">
            <i class="fa-solid fa-people-group"></i>
            <div>
              <strong>Gruppi per livello</strong>
              <span>Classi dedicate per principianti e avanzati.</span>
            </div>
          </div>
          <div class="feature">
            <i class="fa-solid fa-heart-pulse"></i>
            <div>
              <strong>Benessere e postura</strong>
              <span>Allenamenti mirati per energia e mobilità.</span>
            </div>
          </div>
          <div class="feature">
            <i class="fa-solid fa-medal"></i>
            <div>
              <strong>Percorsi agonistici</strong>
              <span>Preparazione e crescita con obiettivi chiari.</span>
            </div>
          </div>
          <div class="feature">
            <i class="fa-solid fa-location-dot"></i>
            <div>
              <strong>In centro ad Alcamo</strong>
              <span>Facile da raggiungere, ambiente accogliente.</span>
            </div>
          </div>
        </div>

        <div style="margin-top: 20px;">
          <a class="btn" href="chi-siamo.php">Scopri chi siamo</a>
        </div>
      </div>

      <img src="assets/img/about.svg" alt="Sirio Fit & Dance: informazioni e servizi">
    </div>
  </div>
</section>

<section class="section">
  <div class="container">
    <h2>Vuoi iniziare?</h2>
    <p class="lead">Scrivici e raccontaci cosa ti interessa: ti proponiamo il corso più adatto e le disponibilità per una prova.</p>
    <div style="margin-top: 24px;">
      <a class="btn" href="contatti.php">Contattaci</a>
    </div>
  </div>
</section>

<?php require 'footer.php'; ?>