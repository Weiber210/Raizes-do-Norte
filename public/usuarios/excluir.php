<?php
// Bloqueio de Login
require_once '../auth/verificar.php';

// Permissão
autorizarPerfis(["Administrador"]);

//Conectar ao banco de ddados
require_once '../../config/database.php';  

// Localiza o ID
$id = $_GET['id'] ?? null;
if(!$id){
    die("ID do usuário não informado.");
}
// Buscar o usuário
$sql = "delete from usuarios where id = ?";
$stmt = $pdo->prepare($sql);

$stmt->BindValue(1, $id, PDO::PARAM_INT);
$stmt->execute();

header("Location: index.php");
exit;
?>