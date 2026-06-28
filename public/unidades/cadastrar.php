<?php
// Bloqueio de Login
require_once '../auth/verificar.php';
// Conectar ao banco de dados
require_once '../../config/database.php';

// Verificar se o formulário foi enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    // Obter os dados do formulário
        $nome = $_POST['nome'];
        $cidade = $_POST['cidade'];
        $estado = $_POST['estado'];
        $ativa = ($_POST['ativa'] === 'true');

        // Executar consulta
        $sql = "INSERT INTO unidades (nome, cidade, estado, ativa) 
                VALUES (?, ?, ?, ?)"; 
    
        // Preparar a consulta
        $stmt = $pdo->prepare($sql);

        // vincula valores com tipos explícitos
        $stmt->bindValue(1, $nome, PDO::PARAM_STR);
        $stmt->bindValue(2, $cidade, PDO::PARAM_STR);
        $stmt->bindValue(3, $estado, PDO::PARAM_STR);
        $stmt->bindValue(4, $ativa, PDO::PARAM_BOOL);

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
    <title>Cadastrar Unidade</title>
</head>
<body>
    <h1>Cadastrar Unidade</h1>

    <form method="POST">
        <label>Nome:</label><br>
        <input type="text" id="nome" name="nome" required><br><br>

        <label>Cidade:</label><br>
        <input type="text" id="cidade" name="cidade" required><br><br>

        <label>Estado:</label><br>
        <select name="estado" required>
            <option value="AC">AC</option>
            <option value="AL">AL</option>
            <option value="AM">AM</option>
            <option value="AP">AP</option>
            <option value="BA">BA</option>
            <option value="CE">CE</option>
            <option value="DF">DF</option>
            <option value="ES">ES</option>
            <option value="GO">GO</option>
            <option value="MA">MA</option>
            <option value="MG">MG</option>
            <option value="MS">MS</option>
            <option value="MT">MT</option>
            <option value="PA">PA</option>
            <option value="PB">PB</option>
            <option value="PE">PE</option>
            <option value="PI">PI</option>
            <option value="PR">PR</option>
            <option value="RJ">RJ</option>
            <option value="RN">RN</option>
            <option value="RO">RO</option>
            <option value="RR">RR</option>
            <option value="RS">RS</option>
            <option value="SC">SC</option>
            <option value="SE">SE</option>
            <option value="SP">SP</option>
            <option value="TO">TO</option>
        </select><br><br>

        <label>Ativa:</label><br>
        <select name="ativa" required>
            <option value="true">Sim</option>
            <option value="false">Não</option>
        </select><br><br>

        <button type="submit">Cadastrar</button>
        <a href="index.php">Cancelar</a>
    </form>
</body>
</html>
