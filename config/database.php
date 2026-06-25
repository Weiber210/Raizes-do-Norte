// localização e conexão com o banco
<?php
$host = "localhost";
$db = "raizes_do_norte";
$user = "root";
$pass = "";   

// Tentativa de conexão
try{
$pdo = new PDO(
    "mysql:host=$host;dbname=$db;charset=utf8",
    $user,
    $pass
);

// Mostrar o erro
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}

catch(Exception $erro){

// Mostrar mensagem
die("ERRO AO CONECTAR AO BANCO DE DADOS: ".$erro->getMessage());
}
?>