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

<?php
$tituloPagina = "Usuários";
require dirname(__DIR__) . "/componentes/cabecalho.php";
?>

    <h1 class="titulo-pagina mb-4">Usuários</H1>
    
        <a class="btn btn-primary" href="cadastrar.php">Novo Usuário</a>
        <a class="btn btn-secondary"  href="../dashboard.php">Voltar</a>

        <br><br> 

    <?php if (isset($_GET["sucesso"])) { ?>
        <div class="alert alert-success"><?= htmlspecialchars($_GET["sucesso"], ENT_QUOTES, "UTF-8") ?></div>
    <?php } ?>

    <?php if (isset($_GET["erro"])) { ?>
        <div class="alert alert-danger"><?= htmlspecialchars($_GET["erro"], ENT_QUOTES, "UTF-8") ?></div>
    <?php } ?>

    <div class="table-responsive mt-4">
    <table class="table table-striped table-hover align-middle">
        <tr>
            <th>ID</th>
            <th>Nome</th>
            <th>Email</th>
            <th>Perfil</th>
            <th>Data de Criação</th>
            <th>Consentimento LGPD</th>
            <th>Status</th>
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
                <td><?= $usuario["ativo"] ? "Ativo" : "Inativo" ?></td>
                <td class="actions">
                    <a class="btn btn-primary" href="editar.php?id=<?= $usuario['id'] ?>">Editar</a>
                    <?php if ($usuario["ativo"]) { ?>
                        <form method="POST" action="excluir.php" style="display: inline;" onsubmit="return confirm(&quot;Tem certeza que deseja desativar este usuário?&quot;);"><input type="hidden" name="id" value="<?= (int) $usuario["id"] ?>"><button class="btn btn-secondary" type="submit">Desativar</button></form>
                    <?php } ?>
                </td>
        </tr>
            <?php } ?>
    </table>
    </div>
<?php require dirname(__DIR__) . "/componentes/rodape.php"; ?>
