<?php

function admin_require_login(): void
{
    if (session_status() !== PHP_SESSION_ACTIVE) {
        session_start();
    }

    if (empty($_SESSION['admin_logged'])) {
        header('Location: index.php');
        exit;
    }
}
