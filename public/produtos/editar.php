<?php
// Bloqueio de Login
require_once '../auth/verificar.php';

// Permissão
autorizarPerfis(["Administrador", "Gerente"]);

//Conectar ao banco de dados
require_once '../../config/database.php';

// Localiza o ID
$id = $_GET['id'] ?? null;
if(!$id){
    die("ID do produto não informado.");
}
// Buscar o produto
$sql = "select * from produtos where id = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$id]);
$produto = $stmt->fetch(PDO::FETCH_ASSOC);
if(!$produto){
    die("Produto não encontrado.");
}

// Atualizar o produto
if ($_SERVER['REQUEST_METHOD'] === 'POST'){

    $nome = $_POST['nome'];
    $descricao = $_POST['descricao'];
    $preco = $_POST['preco'] ?? "";
    $preco = str_replace(',', '.', $preco);
    $ativo = ($_POST['ativo'] ?? 'false') === 'true';

    $sql = "update produtos set nome = ?, descricao = ?, preco = ?, ativo = ? where id = ?";
    $stmt = $pdo->prepare($sql);

    // vincula valores
    $stmt->bindValue(1, $nome, PDO::PARAM_STR);
    $stmt->bindValue(2, $descricao, PDO::PARAM_STR);
    $stmt->bindValue(3, $preco, PDO::PARAM_STR);
    $stmt->bindValue(4, $ativo, PDO::PARAM_BOOL);
    $stmt->bindValue(5, $id, PDO::PARAM_INT);

    $stmt->execute();

    header("Location: index.php");
    exit;
}
?>

<?php
$tituloPagina = "Editar Produtos";
require dirname(__DIR__) . "/componentes/cabecalho.php";
?>

    <h1 class="titulo-pagina mb-4">Editar</h1>
    <form method="POST">

     

        <label>Nome:</label><br>
        <input class="form-control" type="text" name="nome" value="<?= $produto['nome'] ?>" required><br><br>

        <label>Descrição:</label><br>
        <textarea class="form-control" name="descricao" required><?= $produto['descricao'] ?></textarea><br><br>

        <label>Preço:</label><br>
        <input class="form-control" type="number" step="0.01" name="preco" value="<?= $produto['preco'] ?>" required><br><br>

        <label>Status:</label><br>
        <select class="form-select" name="ativo" required>

            <option value="true" <?= ($produto['ativo'] == true) ? 'selected' : '' ?>>Ativo</option>
            <option value="false" <?= ($produto['ativo'] == false) ? 'selected' : '' ?>> Inativo</option>
        </select><br><br>

        <button class="btn btn-primary" type="submit">Salvar</button>
        <a class="btn btn-secondary" href="index.php">Cancelar</a>

     </form>
<?php require dirname(__DIR__) . "/componentes/rodape.php"; ?>
