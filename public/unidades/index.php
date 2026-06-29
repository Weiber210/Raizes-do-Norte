<?php
// Bloqueio de Login
require_once '../auth/verificar.php';

// Permissão
autorizarPerfis(["Administrador", "Gerente"]);

//Conectar ao banco de dados
require_once '../../config/database.php';


// Consultar banco
$sql =  "select * from unidades order by id";

// Executar a consulta
$stmt = $pdo->query($sql);

//Guardar dentro de um vetor
$unidades = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<?php
$tituloPagina = "Unidade";
require dirname(__DIR__) . "/componentes/cabecalho.php";
?>
    <h1 class="titulo-pagina mb-4">Unidades</h1>
    
        <a class="btn btn-primary" href="cadastrar.php">Nova Unidade</a>
        <a class="btn btn-secondary" href="../dashboard.php">Voltar</a>

        <br><br> 

    <div class="table-responsive mt-4">
    <table class="table table-striped table-hover align-middle">
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
                    <a class="btn btn-primary" href="editar.php?id=<?= $unidade['id'] ?>">Editar</a>
                    <a class="btn btn-secondary" onclick="return confirm('Tem certeza que deseja excluir esta Unidade?');" href="excluir.php?id=<?= $unidade['id'] ?>">Excluir</a>
                </td>
        </tr>
            <?php } ?>
    </table>
    </div>
<?php require dirname(__DIR__) . "/componentes/rodape.php"; ?>