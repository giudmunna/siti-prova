<?php
session_start();
if (!isset($_SESSION['admin_logged'])) {
    header("Location: index.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="it">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin - Sirio Fit & Dance</title>
  <style>
    body { font-family: Arial, sans-serif; padding: 40px; background: #f4f4f4; }
    .container { max-width: 800px; margin: 0 auto; background: white; padding: 30px; border-radius: 12px; box-shadow: 0 5px 15px rgba(0,0,0,0.1); }
    a { display: inline-block; margin: 10px 0; padding: 12px 20px; background: #ff00aa; color: white; text-decoration: none; border-radius: 8px; }
    a:hover { background: #ff44bb; }
  </style>
</head>
<body>
  <div class="container">
    <h1>👋 Benvenuto nel Pannello Admin</h1>
    <p><strong>Sirio Fit & Dance</strong></p>
    <hr>
    <p><a href="add-event.php">➕ Aggiungi Nuovo Evento</a></p>
    <p><a href="../eventi.php" target="_blank">👀 Visualizza Eventi sul Sito</a></p>
    <p><a href="logout.php">🚪 Logout</a></p>
  </div>
</body>
</html>