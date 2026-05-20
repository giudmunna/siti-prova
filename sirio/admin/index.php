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
        $error = "Credenziali errate!";
    }
}
?>

<!DOCTYPE html>
<html lang="it">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin - Sirio</title>
  <style>
    body { font-family: Arial; background: #111; color: white; display:flex; justify-content:center; align-items:center; height:100vh; }
    form { background:#222; padding:40px; border-radius:12px; width:100%; max-width:350px; }
    input { width:100%; padding:12px; margin:10px 0; border-radius:8px; border:none; }
    button { width:100%; padding:14px; background:#ff00aa; color:white; border:none; border-radius:8px; font-size:1.1rem; }
  </style>
</head>
<body>
  <form method="POST">
    <h2>Accesso Amministratore</h2>
    <?php if($error) echo "<p style='color:red;'>$error</p>"; ?>
    <input type="text" name="username" placeholder="Username" required>
    <input type="password" name="password" placeholder="Password" required>
    <button type="submit">Entra</button>
  </form>
</body>
</html>