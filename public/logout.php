<?php

require_once "../config/app.php";

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

header(
"Cache-Control: no-store, no-cache, must-revalidate, max-age=0, private"
);
header("Pragma: no-cache");
header("Expires: 0");

$_SESSION = [];

if (ini_get("session.use_cookies")) {
    $parametros = session_get_cookie_params();

    setcookie(
        session_name(),
        "",
        time() - 42000,
        $parametros["path"],
        $parametros["domain"],
        $parametros["secure"],
        $parametros["httponly"]
    );
}

session_destroy();

header("Location: " . urlPublica("login.php"));
exit;