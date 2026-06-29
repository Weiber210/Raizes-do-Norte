<?php
// Bloqueio de Login
require_once '../auth/verificar.php';
// Conectar ao Banco de Dados
require_once "../../config/database.php";
// Permissão
autorizarPerfis(["Administrador","Gerente","Atendente"]);

require_once "../../config/database.php";
require_once "../../app/Repositories/PedidoRepository.php";
require_once "../../app/Services/PedidoService.php";
require_once "../../app/Controllers/PedidoController.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    http_response_code(405);
    header("Allow: POST");
    exit("Método não permitido.");
}

$repository = new PedidoRepository($pdo);
$service = new PedidoService($repository);
$controller = new PedidoController($service);

try {
    $usuarioResponsavelId = (int) ($_SESSION["id"] ?? 0);

    $controller->processarPagamento(
        $_POST,
        $usuarioResponsavelId
    );

    $resultado = strtoupper(
        trim((string) $_POST["resultado"])
    );

    header(
        "Location: ../pedidos/index.php?pagamento="
        . rawurlencode($resultado)
        . "&pedido_id="
        . (int) $_POST["pedido_id"]
    );
    exit;
    } catch (InvalidArgumentException $erro) {
        $mensagem = $erro->getMessage();
    } catch (PDOException $erro) {
        error_log($erro->getMessage());
        $mensagem = "Erro ao registrar o pagamento.";
    } catch (RuntimeException $erro) {
        $mensagem = $erro->getMessage();
    } catch (Throwable $erro) {
        error_log($erro->getMessage());
        $mensagem = "Não foi possível processar o pagamento.";
    }

header(
    "Location: ../pedidos/index.php?erro_pagamento="
    . rawurlencode($mensagem)
);
exit;