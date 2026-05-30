<?php
session_start();
$error = '';

if ($_POST) {
    $user = $_POST['username'];
    $pass = $_POST['password'];

    // Cambia queste credenziali!
    if ($user === 'admin' && $pass === 'sirio2026') {  
        $_SESSION['admin_logged'] = true;
        header("Location: dashboard.php");
        exit;
    } else {
        $error = 'Credenziali errate.';
    }
}
?>

<!DOCTYPE html>
<html lang="it">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Accesso Admin — Sirio</title>
  <link rel="stylesheet" href="assets/admin.css">
</head>
<body class="login-page">
  <form class="login-card" method="POST">
    <h2>Accesso amministratore</h2>
    <?php if ($error): ?>
      <p class="login-error"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>
    <input type="text" name="username" placeholder="Username" required autocomplete="username">
    <input type="password" name="password" placeholder="Password" required autocomplete="current-password">
    <button type="submit">Entra</button>
  </form>
</body>
</html>