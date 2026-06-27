<?php
session_start();

// Faz retornar para a página de login
if (!isset($_SESSION["usuario"])) {
    header("Location: login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
</head>
<body>
    <h1>Raízes do Norte</h1>
    <p>Bem-vindo, <?php echo $_SESSION["usuario"]; ?>!</p>
    <p>Perfil: <?php echo $_SESSION["perfil"]; ?></p>

    <hr>
    <h2>Menu</h2>
    <ul>
        <a href="produtos/index.php"><button>Produtos</button></a><br><br>
        <a href="usuarios/index.php"><button>Usuários</button></a>
        <li>Unidades</li>
        <li>Usuários</li>
        <li>Pagamentos</li>
    </ul>
    
    <br>
    <a href="login.php">Sair</a>



</body>
</html>