<?php
//Conectar ao banco de ddados
require_once '../../config/database.php';  

// Localiza o ID
$id = $_GET['id'] ?? null;
if(!$id){
    die("ID do produto não informado.");
}
// Buscar o produto
$sql = "delete from produtos where id = ?";
$stmt = $pdo->prepare($sql);

$stmt->BindValue(1, $id, PDO::PARAM_INT);
$stmt->execute();

header("Location: index.php");
exit;
?>