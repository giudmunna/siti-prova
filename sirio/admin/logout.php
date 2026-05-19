<?php
session_start();

// Cancella solo la variabile admin (più sicuro)
unset($_SESSION['admin_logged']);

// Distrugge completamente la sessione
session_destroy();

// Reindirizza al login
header("Location: index.php");
exit;
?>