<?php 
require_once 'includes/functions.php'; 
require_once 'config.php'; 
?>
<!DOCTYPE html>
<html lang="it">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= $content['site']['name'] ?></title>
  <link rel="stylesheet" href="assets/css/style.css">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
</head>
<body>

<nav class="navbar">
  <div class="logo">SIRIO</div>
  <ul class="nav-links">
    <li><a href="index.php">Home</a></li>
    <li><a href="chi-siamo.php">Chi Siamo</a></li>
    <li><a href="corsi.php">Corsi</a></li>
    <li><a href="galleria.php">Galleria</a></li>
    <li><a href="eventi.php">Eventi</a></li>
    <li><a href="contatti.php">Contatti</a></li>
  </ul>
  <div class="hamburger">☰</div>
</nav>