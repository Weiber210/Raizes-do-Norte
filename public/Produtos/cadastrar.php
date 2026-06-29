<?php
// Bloqueio de Login
require_once '../auth/verificar.php';

// Permissão
autorizarPerfis(["Administrador", "Gerente"]);

// Conectar ao banco de dados
require_once '../../config/database.php';

// Verificar se o formulário foi enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    // Obter os dados do formulário
        $nome = $_POST['nome'];
        $descricao = $_POST['descricao'];
        $preco = $_POST['preco'];
        $preco = str_replace(',', '.', $preco);
        $ativo = ($_POST['ativo'] === 'true');

        // Executar consulta
        $sql = "INSERT INTO produtos (nome, descricao, preco, ativo) 
                VALUES (?, ?, ?, ?)"; 
    
        // Preparar a consulta
        $stmt = $pdo->prepare($sql);

        // vincula valores com tipos explícitos
        $stmt->bindValue(1, $nome, PDO::PARAM_STR);
        $stmt->bindValue(2, $descricao, PDO::PARAM_STR);
        $stmt->bindValue(3, $preco, PDO::PARAM_STR);
        $stmt->bindValue(4, $ativo, PDO::PARAM_BOOL);

        // Executar a consulta
        $stmt->execute();

    // volte para a lista de produtos
    header("Location: index.php");
    exit;
}
?>

<?php
$tituloPagina = "Cadastrar produtos";
require dirname(__DIR__) . "/componentes/cabecalho.php";
?>

    <h1 class="titulo-pagina mb-4">Cadastrar Produto</h1>
    <form method="POST">
        <label>Nome:</label><br>
        <input class="form-control" type="text" id="nome" name="nome" required><br><br>

        <label>Descrição:</label><br>
        <textarea class="form-control" name="descricao" required></textarea><br><br>

        <label for="preco">Preço:</label>
        <input class="form-control" type="number" step="0.01" name="preco" required><br><br>

        <label>Ativo:</label><br>
        <select class="form-select" name="ativo">
            <option value="true">Ativo</option>
            <option value="false">Inativo</option>
        </select><br><br>

        <button class="btn btn-primary" type="submit">Cadastrar</button>
        <a class="btn btn-secondary" href="index.php">Cancelar</a>
    </form>
<?php require dirname(__DIR__) . "/componentes/rodape.php"; ?>
