<?php
// Bloqueio de Login
require_once "../auth/verificar.php";

// Permissão
autorizarPerfis(["Administrador", "Gerente"]);

// Conectar ao banco de dados
require_once "../../config/database.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Allow: POST");
    http_response_code(405);
    exit("Método não permitido.");
}

$id = filter_input(INPUT_POST, "id", FILTER_VALIDATE_INT);

if ($id === false || $id === null || $id <= 0) {
    header("Location: index.php?erro=" . rawurlencode("ID do produto inválido."));
    exit;
}

try {
    $sql = "update produtos set ativo = false where id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(1, $id, PDO::PARAM_INT);
    $stmt->execute();

    header("Location: index.php?sucesso=" . rawurlencode("Produto desativado com sucesso."));
    exit;
} catch (PDOException $e) {
    error_log($e->getMessage());
    header("Location: index.php?erro=" . rawurlencode("Não foi possível desativar o produto."));
    exit;
}
