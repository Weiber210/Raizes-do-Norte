<?php
session_start();

// Faz retornar para a página de login
if (!isset($_SESSION["usuario"])) {
    header("Location: /Raizes-do-Norte/public/login.php");
    exit;
}
 
// Tempo de expiração  30 minutos
$tempo_expiracao = 30 * 60;

// Renovar o tempo
if (isset($_SESSION["ultimo_acesso"]) && (time() - $_SESSION["ultimo_acesso"]) > $tempo_expiracao) {
    // Sessão expirada, redirecionar para a página de login
    session_unset();
    session_destroy();
    header("Location: /Raizes-do-Norte/public/login.php");
    exit;
}

$_SESSION["ultimo_acesso"] = time();

if (($_SESSION["perfil"] ?? "") === "Cliente") {
    http_response_code(403);
    echo "Acesso negado ao painel administrativo.";
    exit;
}


http_response_code(200);