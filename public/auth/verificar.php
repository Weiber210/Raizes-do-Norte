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

http_response_code(200);
 // Bloqueios de acesso com base no perfil do usuário

/*if ($_SESSION["perfil"] !== "Administrador") {
    // Redirecionar para uma página de acesso negado ou exibir uma mensagem de erro
    echo "Acesso negado. Você não tem permissão para acessar esta página.";
    exit;
}
*/

?>