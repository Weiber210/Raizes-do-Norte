<?php
// Bloqueio de Login
require_once '../auth/verificar.php';

// Permissão
autorizarPerfis(["Administrador"]);

//Conectar ao banco de dados
require_once '../../config/database.php';


// Consultar banco
$sql =  "select * from usuarios order by id";

// Executar a consulta
$stmt = $pdo->query($sql);

//Guardar dentro de um vetor
$usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Usuários</title>
</head>
<body>
    <H1>Usuários</H1>
    
        <a href="cadastrar.php"><button>Novo Usuário</button></a>
        <a href="../dashboard.php"><button>Voltar</button></a>

        <br><br> 

    <table border="1" cellpadding="8">
        <tr>
            <th>ID</th>
            <th>Nome</th>
            <th>Email</th>
            <th>Perfil</th>
            <th>Data de Criação</th>
            <th>Consentimento LGPD</th>
            <th>Ações</th>
        </tr>
        <?php foreach($usuarios as $usuario){ ?>
        <tr>
                <td><?= $usuario['id'] ?></td>
                <td><?= $usuario['nome'] ?></td>
                <td><?= $usuario['email'] ?></td>
                <td><?= $usuario['perfil'] ?></td>
                <td><?= $usuario['created_at'] ?></td>
                <td><?php
                    if($usuario['consentimento_lgpd']){
                        echo "Sim";
                    }else{
                        echo "Não";
                    }
                    ?>
                </td>
                <td class="actions">
                    <a href="editar.php?id=<?= $usuario['id'] ?>"><button>Editar</button></a>
                    <a onclick="return confirm('Tem certeza que deseja excluir este usuário?');" href="excluir.php?id=<?= $usuario['id'] ?>"><button>Excluir</button></a>
                </td>
        </tr>
            <?php } ?>
    </table>
</body>
</html>