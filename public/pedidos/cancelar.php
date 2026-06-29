<?php
require_once "../auth/verificar.php";

// Permissão
autorizarPerfis(["Administrador","Gerente","Atendente"]);

require_once "../../config/database.php";
require_once "../../app/Repositories/PedidoRepository.php";
require_once "../../app/Services/PedidoService.php";
require_once "../../app/Controllers/PedidoController.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    http_response_code(405);
    exit("Método não permitido.");
}

$repository = new PedidoRepository($pdo);
$service = new PedidoService($repository);
$controller = new PedidoController($service);

try {
    $pedidoId = filter_var(
        $_POST["pedido_id"] ?? null,
        FILTER_VALIDATE_INT
    );

    if ($pedidoId === false || $pedidoId === null) {
        throw new InvalidArgumentException("Pedido inválido.");
    }

    $controller->cancelar(
        $pedidoId,
        (int) ($_SESSION["id"] ?? 0)
    );

    header(
        "Location: index.php?status_atualizado=" . $pedidoId
    );
    exit;
} catch (Throwable $erro) {
    header(
        "Location: index.php?erro_pagamento="
        . rawurlencode($erro->getMessage())
    );
    exit;
}