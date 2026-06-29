<?php
// Bloqueio de Login
require_once '../auth/verificar.php';

// Permissão
autorizarPerfis(["Administrador"]);

// Conectar ao banco de dados
require_once '../../config/database.php';

// Verificar se o formulário foi enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    // Obter os dados do formulário
        $nome = $_POST['nome'];
        $email = $_POST['email'];
        $perfil = $_POST['perfil'];
        $consentimento_lgpd = ($_POST['consentimento_lgpd'] === 'true');
        $created_at = date('Y-m-d H:i:s');
        $senha = password_hash($_POST['senha'], PASSWORD_DEFAULT);

        // Executar consulta
        $sql = "INSERT INTO usuarios (nome, email, perfil,consentimento_lgpd, created_at, senha) 
                VALUES (?, ?, ?, ?, ?, ?)"; 
    
        // Preparar a consulta
        $stmt = $pdo->prepare($sql);

        // vincula valores com tipos explícitos
        $stmt->bindValue(1, $nome, PDO::PARAM_STR);
        $stmt->bindValue(2, $email, PDO::PARAM_STR);
        $stmt->bindValue(3, $perfil, PDO::PARAM_STR);
        $stmt->bindValue(4, $consentimento_lgpd, PDO::PARAM_BOOL);
        $stmt->bindValue(5, $created_at, PDO::PARAM_STR);
        // Vincula a senha
        $stmt->bindValue(6, $senha, PDO::PARAM_STR);


        // Executar a consulta
        $stmt->execute();

    // volte para a lista de produtos
    header("Location: index.php");
    exit;
}
?>

<?php
$tituloPagina = "Cadastrar Usuarios";
require dirname(__DIR__) . "/componentes/cabecalho.php";
?>
    <h1 class="titulo-pagina mb-4">Cadastrar Usuário</h1>
    <form method="POST">
        <label>Nome:</label><br>
        <input class="form-control" class="form-control" type="text" id="nome" name="nome" required><br><br>

        <label>Email:</label><br>
        <input class="form-control" type="email" id="email" name="email" required><br><br>

        <label>Perfil:</label><br>
        <select class="form-select" name="perfil">
            <option value="Administrador">Administrador</option>
            <option value="Gerente">Gerente</option>
            <option value="Atendente">Atendente</option>
            <option value="Cozinha">Cozinha</option>
            <option value="Cliente">Cliente</option>
        </select><br><br>

        <label>Consentimento LGPD:</label><br>
        <select class="form-select" name="consentimento_lgpd">
            <option value="true">Sim</option>
            <option value="false">Não</option>
        </select><br><br>

        <label>Senha:</label><br>
        <input class="form-control" type="password" id="senha" name="senha" required><br><br>

        <button class="btn btn-primary" type="submit">Cadastrar</button>
        <a class="btn btn-secondary" href="index.php">Cancelar</a>
    </form>
<?php require dirname(__DIR__) . "/componentes/rodape.php"; ?>
