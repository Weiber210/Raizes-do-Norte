<?php

require_once "../auth/verificar.php";
require_once "../../config/database.php";
require_once "../../app/Repositories/PedidoRepository.php";
require_once "../../app/Services/PedidoService.php";
require_once "../../app/Controllers/PedidoController.php";

$repository = new PedidoRepository($pdo);
$service = new PedidoService($repository);
$controller = new PedidoController($service);

$mensagemErro = "";
$pedidoIdInformado = $_POST["pedido_id"] ?? $_GET["id"] ?? null;
$pedidoId = filter_var($pedidoIdInformado, FILTER_VALIDATE_INT);

if ($pedidoId === false || $pedidoId === null) {
    http_response_code(400);
    exit("Pedido inválido.");
    }

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    try {
        $controller->atualizarStatus(
            $_POST,
            (int) ($_SESSION["id"] ?? 0),
            (string) ($_SESSION["perfil"] ?? "")
        );

        header("Location: index.php?status_atualizado=" . $pedidoId);
        exit;
    } catch (InvalidArgumentException $erro) {
        $mensagemErro = $erro->getMessage();
    } catch (PDOException $erro) {
        error_log($erro->getMessage());
        $mensagemErro = "Erro ao atualizar o pedido.";
    } catch (RuntimeException $erro) {
        $mensagemErro = $erro->getMessage();
    } catch (Throwable $erro) {
        error_log($erro->getMessage());
        $mensagemErro = "Não foi possível atualizar o status.";
    }
    }

    try {
    $pedido = $controller->formularioEdicao($pedidoId);
    } catch (Throwable $erro) {
    http_response_code(404);
    exit("Pedido não encontrado.");
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Atualizar o Status</title>
</head>
<body>

    <h1>Atualizar Status</h1>
    
    <?php if ($mensagemErro !== "") { ?>
    <p><?= htmlspecialchars($mensagemErro) ?></p>
    <?php } ?>

    <p><strong>Pedido:</strong> <?= (int) $pedido["id"] ?></p>
    <p><strong>Cliente:</strong> <?= htmlspecialchars($pedido["cliente"]) ?></p>
    <p><strong>Unidade:</strong> <?= htmlspecialchars($pedido["unidade"]) ?></p>
    <p><strong>Status atual:</strong> <?= htmlspecialchars(str_replace("_", " ", $pedido["status"])) ?></p>

    <?php if ($pedido["proximo_status"] !== null) { ?>
        <form method="POST">
            <input type="hidden" name="pedido_id" value="<?= (int) $pedido["id"] ?>">
            <input type="hidden" name="novo_status" value="<?= htmlspecialchars($pedido["proximo_status"]) ?>">
            <button type="submit">Atualizar para <?= htmlspecialchars(str_replace("_", " ", $pedido["proximo_status"])) ?></button>
        </form>
    <?php } else { ?>
        <p>Este pedido não permite nova atualização de status.</p>
    <?php } ?>

    <br>
    <a href="index.php">Voltar</a>


</body>
</html>