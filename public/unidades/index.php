<?php
// Bloqueio de Login
require_once '../auth/verificar.php';
//Conectar ao banco de dados
require_once '../../config/database.php';


// Consultar banco
$sql =  "select * from unidades order by id";

// Executar a consulta
$stmt = $pdo->query($sql);

//Guardar dentro de um vetor
$unidades = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Unidades</title>
</head>
<body>
    <H1>Unidades</H1>
    
        <a href="cadastrar.php"><button>Novo Unidade</button></a>
        <a href="../dashboard.php"><button>Voltar</button></a>

        <br><br> 

    <table border="1" cellpadding="8">
        <tr>
            <th>ID</th>
            <th>Nome</th>
            <th>Cidade</th>
            <th>Estado</th>
            <th>Ativa</th>
            <th>Ações</th>
        </tr>
        <?php foreach($unidades as $unidade){ ?>
        <tr>
                <td><?= $unidade['id'] ?></td>
                <td><?= $unidade['nome'] ?></td>
                <td><?= $unidade['cidade'] ?></td>
                <td><?= $unidade['estado'] ?></td>
                <td><?php
                    if($unidade['ativa']){
                        echo "Sim";
                    }else{
                        echo "Não";
                    }
                    ?>
                </td>
                <td class="actions">
                    <a href="editar.php?id=<?= $unidade['id'] ?>"><button>Editar</button></a>
                    <a onclick="return confirm('Tem certeza que deseja excluir este produto?');" href="excluir.php?id=<?= $unidade['id'] ?>"><button>Excluir</button></a>
                </td>
        </tr>
            <?php } ?>
    </table>
</body>
</html>