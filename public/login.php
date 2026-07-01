<?php
require_once "../config/app.php";
//  Conectando com o banco de dados
require_once '../config/database.php';
session_start();

header(
"Cache-Control: no-store, no-cache, must-revalidate, max-age=0, private"
);
header("Pragma: no-cache");
header("Expires: 0");

if (isset($_SESSION["usuario"])) {
    header("Location: " . urlPublica("dashboard.php"));
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $email = $_POST["email"];
    $senha = $_POST["senha"];


    $sql = "select * from usuarios WHERE LOWER(email) = LOWER(?) AND ativo = true";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$email]);

    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

    
    if ($usuario && password_verify($senha, $usuario["senha"])) {
        session_regenerate_id(true);
        $_SESSION["id"] = $usuario["id"];
        $_SESSION["usuario"] = $usuario["nome"];
        $_SESSION["email"] = $usuario["email"];
        $_SESSION["perfil"] = $usuario["perfil"];
        $_SESSION["ultimo_acesso"] = time();

        header("Location: " . urlPublica("dashboard.php"));
        exit;

    } else {
        echo "Email ou senha inválidos!";
    }
}
?>

<?php
$tituloPagina = "Login";
require __DIR__ . "/componentes/cabecalho.php";
?>
    <h1 class="titulo-pagina mb-4">Raízes do Nordeste</h1>
    
    <form method="POST" action="login.php">
        <input class="form-control" type="email" name="email" placeholder="Email" required><br>
        <input class="form-control" type="password" name="senha" placeholder="Senha" required><br>
        <button  class="btn btn-primary" type="submit">Entrar</button>

    </form>

<?php require __DIR__ . "/componentes/rodape.php"; ?>


