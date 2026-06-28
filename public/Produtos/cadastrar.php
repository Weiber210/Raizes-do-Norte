<?php
// Bloqueio de Login
require_once '../auth/verificar.php';
// Conectar ao banco de dados
require_once '../../config/database.php';

// Verificar se o formulário foi enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    // Obter os dados do formulário
        $nome = $_POST['nome'];
        $descricao = $_POST['descricao'];
        $preco = $_POST['preco'];
        $preco = str_replace(',', '.', $preco);
        $ativo = ($_POST['ativo'] === 'true');

        // Executar consulta
        $sql = "INSERT INTO produtos (nome, descricao, preco, ativo) 
                VALUES (?, ?, ?, ?)"; 
    
        // Preparar a consulta
        $stmt = $pdo->prepare($sql);

        // vincula valores com tipos explícitos
        $stmt->bindValue(1, $nome, PDO::PARAM_STR);
        $stmt->bindValue(2, $descricao, PDO::PARAM_STR);
        $stmt->bindValue(3, $preco, PDO::PARAM_STR);
        $stmt->bindValue(4, $ativo, PDO::PARAM_BOOL);

        // Executar a consulta
        $stmt->execute();

    // volte para a lista de produtos
    header("Location: index.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="pt-br">
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
        <a href="index.php">Cancelar</a>
    </form>
</body>
</html>
