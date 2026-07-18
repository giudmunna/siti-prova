<?php
/** @var string $pageTitle */
/** @var string $activePage */
?>
<!DOCTYPE html>
<html lang="it">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= htmlspecialchars($pageTitle) ?> — Admin Sirio</title>
  <link rel="stylesheet" href="assets/admin.css">
</head>
<body>
  <header class="admin-header">
    <div class="admin-header-inner">
      <a class="admin-brand" href="dashboard.php">SIRIO <span>Admin</span></a>
      <nav class="admin-nav">
        <a class="<?= ($activePage ?? '') === 'dashboard' ? 'active' : '' ?>" href="dashboard.php">Dashboard</a>
        <a class="<?= ($activePage ?? '') === 'corsi' ? 'active' : '' ?>" href="corsi.php">Corsi</a>
        <a class="<?= ($activePage ?? '') === 'testi' ? 'active' : '' ?>" href="testi.php">Testi</a>
        <a class="<?= ($activePage ?? '') === 'prenotazioni' ? 'active' : '' ?>" href="prenotazioni.php">Prenotazioni</a>
        <a class="<?= ($activePage ?? '') === 'add-event' ? 'active' : '' ?>" href="add-event.php">Nuovo evento</a>
        <a href="../eventi.php" target="_blank" rel="noopener">Vedi sito</a>
        <a href="logout.php">Logout</a>
      </nav>
    </div>
  </header>
  <main class="admin-main">
