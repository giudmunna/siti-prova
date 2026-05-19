<?php
require 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome      = strip_tags(trim($_POST["nome"]));
    $email     = filter_var(trim($_POST["email"]), FILTER_SANITIZE_EMAIL);
    $oggetto   = strip_tags(trim($_POST["oggetto"]));
    $messaggio = strip_tags(trim($_POST["messaggio"]));

    $to = EMAIL_DESTINATARIO;
    $subject = "Nuovo messaggio da Sirio - " . $oggetto;
    $body = "Nome: $nome\nEmail: $email\n\nMessaggio:\n$messaggio";
    $headers = "From: $email";

    if (mail($to, $subject, $body, $headers)) {
        header("Location: contatti.php?status=success");
    } else {
        header("Location: contatti.php?status=error");
    }
    exit;
}
?>