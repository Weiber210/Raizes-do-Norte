<?php
require_once dirname(__DIR__, 2) . "/config/app.php";

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

// Faz retornar para a página de login
if (!isset($_SESSION["usuario"])) {
    header("Location: " . urlPublica("login.php"));
    exit;
}
 
// Tempo de expiração  30 minutos
$tempo_expiracao = 30 * 60;

// Renovar o tempo
if (isset($_SESSION["ultimo_acesso"]) && (time() - $_SESSION["ultimo_acesso"]) > $tempo_expiracao) {
    // Sessão expirada, redirecionar para a página de login
    session_unset();
    session_destroy();
    header("Location: " . urlPublica("login.php"));
    exit;
}

$_SESSION["ultimo_acesso"] = time();

if (($_SESSION["perfil"] ?? "") === "Cliente") {
    http_response_code(403);
     exit("Acesso negado ao painel administrativo.");
}

function autorizarPerfis(array $perfisPermitidos): void
{
    $perfil = $_SESSION["perfil"] ?? "";

    if (!in_array($perfil, $perfisPermitidos, true)) {
        http_response_code(403);
        exit("Perfil sem permissão para acessar esta página.");
    }
}
