<?php
//localização e conexão com o banco de dados
require_once "env.php";

//Dados do banco
$host = $_ENV['DB_HOST'];
$port = $_ENV['DB_PORT'];
$db = $_ENV['DB_NAME'];
$user = $_ENV['DB_USER'];
$pass = $_ENV['DB_PASS'];

// Tentativa de conexão
try{
$pdo = new PDO(
    "pgsql:host=$host;port=$port;dbname=$db",
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