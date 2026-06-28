<?php
//  Conectando com o banco de dados
require_once '../config/database.php';
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $email = $_POST["email"];
    $senha = $_POST["senha"];


    $sql = "select * from usuarios WHERE email = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$email]);

    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($usuario && password_verify($senha, $usuario["senha"])) {

        $_SESSION["id"] = $usuario["id"];
        $_SESSION["usuario"] = $usuario["nome"];
        $_SESSION["email"] = $usuario["email"];
        $_SESSION["perfil"] = $usuario["perfil"];
        $_SESSION["ultimo_acesso"] = time();

        header("Location: /Raizes-do-Norte/public/dashboard.php");
        exit;

    } else {
        echo "Email ou senha inválidos!";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <style>body {
            background-color: #121212;
            color: #ffffff;/
            font-family: Arial, sans-serif;
        }</style>
</head>
<body>
    <h2>Raízes do Norte</h2>
    
    <form method="POST" action="login.php">
        <input type="email" name="email" placeholder="Email" required><br>
        <input type="password" name="senha" placeholder="Senha" required><br>
        <button type="submit">Entrar</button>

    </form>

</body>
</html>



