<?php
session_start();

// Faz retornar para a página de login
if (!isset($_SESSION["usuario"])) {
    header("Location: login.php");
    exit;
}
?>
// Menu e informações do usuário logado
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
</head>
<body>
    <h1>Dashboard Raízes do Norte</h1>
    <p>Bem-vindo, <?php echo $_SESSION["usuario"]; ?>!</p>
    <p>Perfil: <?php echo $_SESSION["perfil"]; ?></p>

    <hr>
    <h2>Menu</h2>
    <ul>
        <li>Produtos</li>
        <li>Pedidos</li>
        <li>Unidades</li>
        <li>Usuários</li>
        <li>Pagamentos</li>
    </ul>
    
    <br>
    <a href="logout.php">Sair</a>



</body>
</html>