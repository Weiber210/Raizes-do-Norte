<?php
// Bloqueio de Login
require_once '../auth/verificar.php';

// Permissão
autorizarPerfis(["Administrador", "Gerente"]);

//Conectar ao banco de dados
require_once '../../config/database.php';


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
                    <a href="editar.php?id=<?= $produto['id'] ?>"><button>Editar</button></a>
                    <a onclick="return confirm('Tem certeza que deseja excluir este produto?');" href="excluir.php?id=<?= $produto['id'] ?>"><button>Excluir</button></a>
                </td>
        </tr>
            <?php } ?>
    </table>
    </div>
<?php require dirname(__DIR__) . "/componentes/rodape.php"; ?>