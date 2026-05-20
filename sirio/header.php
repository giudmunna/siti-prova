<?php 
require_once 'includes/functions.php'; 
require_once 'config.php'; 
$currentPage = basename($_SERVER['PHP_SELF'] ?? 'index.php');
$isActive = function(string $file) use ($currentPage): string {
  return $currentPage === $file ? 'active' : '';
};
?>
<!DOCTYPE html>
<html lang="it">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= $content['site']['name'] ?></title>
  <meta name="description" content="<?= $content['site']['tagline'] ?>. Corsi di danza e fitness ad Alcamo.">
  <link rel="stylesheet" href="assets/css/style.css">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
</head>
<body>

<a class="skip-link" href="#contenuto">Vai al contenuto</a>

<nav class="navbar">
  <a class="logo" href="index.php" aria-label="<?= $content['site']['name'] ?>">
    SIRIO <small>Alcamo</small>
  </a>
  <ul class="nav-links">
    <li><a class="<?= $isActive('index.php') ?>" href="index.php">Home</a></li>
    <li><a class="<?= $isActive('chi-siamo.php') ?>" href="chi-siamo.php">Chi Siamo</a></li>
    <li><a class="<?= $isActive('corsi.php') ?>" href="corsi.php">Corsi</a></li>
    <li><a class="<?= $isActive('galleria.php') ?>" href="galleria.php">Galleria</a></li>
    <li><a class="<?= $isActive('eventi.php') ?>" href="eventi.php">Eventi</a></li>
    <li><a class="<?= $isActive('contatti.php') ?>" href="contatti.php">Contatti</a></li>
  </ul>
  <div class="hamburger">☰</div>
</nav>

<main id="contenuto" class="page">