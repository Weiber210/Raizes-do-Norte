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
    $pass,
    [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES => false
    ]

);

} catch (Throwable $erro) {
error_log($erro->getMessage());

http_response_code(500);
exit("Não foi possível conectar ao banco de dados.");
}