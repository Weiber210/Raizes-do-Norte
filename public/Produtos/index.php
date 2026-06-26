<?php
//Conectar ao banco de dados
require_once '../config/database.php';


// Consultar banco
$sql =  "select * from produtos order by id";

// Executar a consulta
$stmt = $pdo->query($sql);

//Guardar dentro de um vetor
$produtos = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Produtos</title>
</head>
<body>
    <H1>Produtos</H1>
</body>
</html>