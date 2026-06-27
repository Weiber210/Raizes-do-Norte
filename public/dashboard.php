<?php
// Bloqueio de Login
require_once 'auth/verificar.php';


header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
header("Expires: Sat, 26 Jul 1997 05:00:00 GMT");

?>

//Verificar Login
<script>
    fetch('auth/verificar.php')
        .then(response => {
            if (response.status === 200) {
                console.log("sessão Ativa")
            }
        })
        .catch(error => {
            console.error('Erro ao verificar login:', error);
            window.location.href = 'login.php';
        });
</script>

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
        <li>Pedidos</li>
        <li>Pagamentos</li>
    </ul>
    
    <br>
    <a href="logout.php">Sair</a>



</body>
</html>