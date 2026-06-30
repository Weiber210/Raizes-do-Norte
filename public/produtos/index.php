<?php
// Bloqueio de Login
require_once '../auth/verificar.php';
//Conectar ao banco de dados
require_once '../../config/database.php';
// Permissão
autorizarPerfis(["Administrador", "Gerente"]);

// Consultar banco
$sql =  "select * from produtos order by id";

// Executar a consulta
$stmt = $pdo->query($sql);

//Guardar dentro de um vetor
$produtos = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<?php
$tituloPagina = "Produtos";
require dirname(__DIR__) . "/componentes/cabecalho.php";
?>
    <h1 class="titulo-pagina mb-4">Produtos</H1>
    
        <a class="btn btn-primary" href="cadastrar.php">Novo Produto</a>
        <a class="btn btn-secondary" href="../dashboard.php">Voltar</a>

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
            <th>Descrição</th>
            <th>Preço</th>
            <th>Status</th>
            <th>Ações</th>
        </tr>
        <?php foreach($produtos as $produto){ ?>
        <tr>
                <td><?= $produto['id'] ?></td>
                <td><?= $produto['nome'] ?></td>
                <td><?= $produto['descricao'] ?></td>
                <td>R$ <?= number_format($produto['preco'], 2, ',', '.') ?></td>
                <td><?php
                    if($produto['ativo']){
                        echo "Ativo";
                    }else{
                        echo "Inativo";
                    }
                    ?>
                </td>
                <td class="actions">
                    <a class="btn btn-primary" href="editar.php?id=<?= $produto['id'] ?>">Editar</a>
                    <?php if ($produto["ativo"]) { ?>
                        <form method="POST" action="excluir.php" style="display: inline;" onsubmit="return confirm(&quot;Tem certeza que deseja desativar este produto?&quot;);"><input type="hidden" name="id" value="<?= (int) $produto["id"] ?>"><button class="btn btn-secondary" type="submit">Desativar</button></form>
                    <?php } ?>
                </td>
        </tr>
            <?php } ?>
    </table>
    </div>
<?php require dirname(__DIR__) . "/componentes/rodape.php"; ?>
