<?php
//Conectar ao banco de dados
require_once '../../config/database.php';


// Consultar banco
$sql =  "select * from produtos order by id";

// Executar a consulta
$stmt = $pdo->query($sql);

//Guardar dentro de um vetor
$produtos = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Produtos</title>
</head>
<body>
    <H1>Produtos</H1>
    
       <a href="cadastrar.php">
        <button>Novo Produto</button>
        </a>
        <br><br> 

    <table border="1" cellpadding="8">
        <tr>
            <th>ID</th>
            <th>Nome</th>
            <th>Descrição</th>
            <th>Preço</th>
            <th>Status</th>
        <tr>
        <?php foreach($produtos as $produto){ ?>
            <tr>
                <td><?= $produto['id'] ?></td>
                <td><?= $produto['nome'] ?></td>
                <td><?= $produto['descricao'] ?></td>
                <td>R$ <?= number_format($produto['preco'], 2, ',', '.') ?></td>
                <td>
                    <?php
                    if($produto['ativo']){
                        echo "Ativo";
                    }else{
                        echo "Inativo";
                    }
                    ?>
                </td>

            
            </tr>
            <?php } ?>
    </table>
</body>
</html>