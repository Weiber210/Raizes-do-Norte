<?php
// Conectar ao banco de dados
require_once '../../config/database.php';

// Verificar se o formulário foi enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    // Obter os dados do formulário
    $nome = $_POST['nome'];
    $descricao = $_POST['descricao'];
    $preco = $_POST['preco'];
    $ativo = ($_POST['ativo'] === 'true');

    // Exeecutar consulta
    $sql = "INSERT INTO produtos (nome, descricao, preco, ativo) 
            VALUES (?, ?, ?, ?)"; 
    
    // Preparar a consulta
    $stmt = $pdo->prepare($sql);

    // Executar a consulta
    $stmt->execute([$nome, $descricao, $preco, $ativo]);

    // volte para a lista de produtos
    header("Location: index.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastrar Produto</title>
</head>
<body>
    <h1>Cadastrar Produto</h1>
    <form method="POST" action="">
        <label>Nome:</label><br>
        <input type="text" id="nome" name="nome" required><br><br>

        <label>Descrição:</label><br>
        <textarea name="descricao" required></textarea><br><br>

        <label for="preco">Preço:</label>
        <input type="number" step="0.01" name="preco" required><br><br>

        <label>Ativo:</label><br>
        <select name="ativo">
            <option value="true">Ativo</option>
            <option value="false">Inativo</option>
        </select><br><br>

        <button type="submit">Cadastrar</button>
    </form>
</body>
</html>
