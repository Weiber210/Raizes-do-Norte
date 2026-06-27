<?php
// Bloqueio de Login
require_once '../auth/verificar.php';
//Conectar ao banco de dados
require_once '../../config/database.php';

// Localiza o ID
$id = $_GET['id'] ?? null;
if(!$id){
    die("ID do usuário não informado.");
}
// Buscar o usuário
$sql = "select * from usuarios where id = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$id]);
$usuario = $stmt->fetch(PDO::FETCH_ASSOC);
if(!$usuario){
    die("Usuário não encontrado.");
}

// Atualizar o usuário
if ($_SERVER['REQUEST_METHOD'] === 'POST'){

    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $perfil = $_POST['perfil'];
    $consentimento_lgpd = ($_POST['consentimento_lgpd'] === 'true');
    $senha = $_POST['senha'] ? password_hash($_POST['senha'], PASSWORD_DEFAULT) : $usuario['senha'];

    $sql = "update usuarios set nome = ?, email = ?, perfil = ?, consentimento_lgpd = ?, senha = ? where id = ?";
    $stmt = $pdo->prepare($sql);

    // vincula valores
    $stmt->bindValue(1, $nome, PDO::PARAM_STR);
    $stmt->bindValue(2, $email, PDO::PARAM_STR);
    $stmt->bindValue(3, $perfil, PDO::PARAM_STR);
    $stmt->bindValue(4, $consentimento_lgpd, PDO::PARAM_BOOL);
    $stmt->bindValue(5, $senha, PDO::PARAM_STR);
    $stmt->bindValue(6, $id, PDO::PARAM_INT);

    $stmt->execute();

    header("Location: index.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Usuário</title>
</head>
<body>
    <h1>Editar Usuário</h1>
    <form method="POST">

     

        <label>Nome:</label><br>
        <input type="text" name="nome" value="<?= $usuario['nome'] ?>"><br><br>

        <label>Email:</label><br>
        <input type="email" name="email" value="<?= $usuario['email'] ?>"><br><br>

        <label>Perfil:</label><br>
        <select name="perfil">
            <option value="Administrador" <?= ($usuario['perfil'] == 'Administrador') ? 'selected' : '' ?>>Administrador</option>
            <option value="Usuário" <?= ($usuario['perfil'] == 'Usuário') ? 'selected' : '' ?>>Usuário</option>
        </select><br><br>

        <label>Consentimento LGPD:</label><br>
        <select name="consentimento_lgpd">
            <option value="true" <?= ($usuario['consentimento_lgpd'] == true) ? 'selected' : '' ?>>Sim</option>
            <option value="false" <?= ($usuario['consentimento_lgpd'] == false) ? 'selected' : '' ?>>Não</option>
        </select><br><br>

        <label>Senha:</label><br>
        <input type="password" name="senha"><br><br>

        <button type="submit">Salvar</button>
        <a href="index.php">Cancelar</a>

     </form>
</body>
</html>
