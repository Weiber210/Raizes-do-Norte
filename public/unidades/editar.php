<?php
// Bloqueio de Login
require_once '../auth/verificar.php';
//Conectar ao banco de dados
require_once '../../config/database.php';

// Localiza o ID
$id = $_GET['id'] ?? null;
if(!$id){
    die("ID da Unidade não informado.");
}
// Buscar o Unidade
$sql = "select * from unidades where id = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$id]);
$unidade = $stmt->fetch(PDO::FETCH_ASSOC);
if(!$unidade){
    die("Unidade não encontrada.");
}

// Atualizar o produto
if ($_SERVER['REQUEST_METHOD'] === 'POST'){

    $nome = $_POST['nome'];
    $cidade = $_POST['cidade'];
    $estado = $_POST['estado'];
    $ativa = ($_POST['ativa'] ?? 'false') === 'true';

    $sql = "update unidades set nome = ?, cidade = ?, estado = ?, ativa = ? where id = ?";
    $stmt = $pdo->prepare($sql);

    // vincula valores
    $stmt->bindValue(1, $nome, PDO::PARAM_STR);
    $stmt->bindValue(2, $cidade, PDO::PARAM_STR);
    $stmt->bindValue(3, $estado, PDO::PARAM_STR);
    $stmt->bindValue(4, $ativa, PDO::PARAM_BOOL);
    $stmt->bindValue(5, $id, PDO::PARAM_INT);

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
    <title>Editar Unidade</title>
</head>
<body>
    <h1>Editar</h1>
    <form method="POST">
     

        <label>Nome:</label><br>
        <input type="text" name="nome" value="<?= $unidade['nome'] ?>" required><br><br>

        <label>Cidade:</label><br>
        <input type="text" id="cidade" name="cidade" value="<?= $unidade['cidade']?>" required><br><br>

            <label>Estado:</label><br>
        <select name="estado">
            <option value="AC" <?= ($unidade['estado'] == "AC") ? 'selected' : '' ?>>AC</option>
            <option value="AL" <?= ($unidade['estado'] == "AL") ? 'selected' : '' ?>>AL</option>
            <option value="AM" <?= ($unidade['estado'] == "AM") ? 'selected' : '' ?>>AM</option>
            <option value="AP" <?= ($unidade['estado'] == "AP") ? 'selected' : '' ?>>AP</option>
            <option value="BA" <?= ($unidade['estado'] == "BA") ? 'selected' : '' ?>>BA</option>
            <option value="CE" <?= ($unidade['estado'] == "CE") ? 'selected' : '' ?>>CE</option>
            <option value="DF" <?= ($unidade['estado'] == "DF") ? 'selected' : '' ?>>DF</option>
            <option value="ES" <?= ($unidade['estado'] == "ES") ? 'selected' : '' ?>>ES</option>
            <option value="GO" <?= ($unidade['estado'] == "GO") ? 'selected' : '' ?>>GO</option>
            <option value="MA" <?= ($unidade['estado'] == "MA") ? 'selected' : '' ?>>MA</option>
            <option value="MG" <?= ($unidade['estado'] == "MG") ? 'selected' : '' ?>>MG</option>
            <option value="MS" <?= ($unidade['estado'] == "MS") ? 'selected' : '' ?>>MS</option>
            <option value="MT" <?= ($unidade['estado'] == "MT") ? 'selected' : '' ?>>MT</option>
            <option value="PA" <?= ($unidade['estado'] == "PA") ? 'selected' : '' ?>>PA</option>
            <option value="PB" <?= ($unidade['estado'] == "PB") ? 'selected' : '' ?>>PB</option>
            <option value="PE" <?= ($unidade['estado'] == "PE") ? 'selected' : '' ?>>PE</option>
            <option value="PI" <?= ($unidade['estado'] == "PI") ? 'selected' : '' ?>>PI</option>
            <option value="PR" <?= ($unidade['estado'] == "PR") ? 'selected' : '' ?>>PR</option>
            <option value="RJ" <?= ($unidade['estado'] == "RJ") ? 'selected' : '' ?>>RJ</option>
            <option value="RN" <?= ($unidade['estado'] == "RN") ? 'selected' : '' ?>>RN</option>
            <option value="RO" <?= ($unidade['estado'] == "RO") ? 'selected' : '' ?>>RO</option>
            <option value="RR" <?= ($unidade['estado'] == "RR") ? 'selected' : '' ?>>RR</option>
            <option value="RS" <?= ($unidade['estado'] == "RS") ? 'selected' : '' ?>>RS</option>
            <option value="SC" <?= ($unidade['estado'] == "SC") ? 'selected' : '' ?>>SC</option>
            <option value="SE" <?= ($unidade['estado'] == "SE") ? 'selected' : '' ?>>SE</option>
            <option value="SP" <?= ($unidade['estado'] == "SP") ? 'selected' : '' ?>>SP</option>
            <option value="TO" <?= ($unidade['estado'] == "TO") ? 'selected' : '' ?>>TO</option>
        </select><br><br>

        <label>Ativa:</label><br>
        <select name="ativa" required>
            <option value="true" <?= ($unidade['ativa'] == true) ? 'selected' : '' ?>>Sim</option>
            <option value="false" <?= ($unidade['ativa'] == false) ? 'selected' : '' ?>> Não</option>
        </select><br><br>

        <button type="submit">Salvar</button>
        <a href="index.php">Cancelar</a>

     </form>
</body>
</html>



