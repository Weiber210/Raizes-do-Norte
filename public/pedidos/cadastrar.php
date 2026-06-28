<?php
// Bloqueio de Login
require_once '../auth/verificar.php';
// Conectar ao banco de dados
require_once '../../config/database.php';

require_once "../../app/Repositories/PedidoRepository.php";
require_once "../../app/Services/PedidoService.php";
require_once "../../app/Controllers/PedidoController.php";

$repository = new PedidoRepository($pdo);
$service = new PedidoService($repository);
$controller = new PedidoController($service);

$mensagemErro = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    try {
    $usuarioResponsavelId = (int) ($_SESSION["id"] ?? 0);

    $pedidoId = $controller->cadastrar(
        $_POST,
        $usuarioResponsavelId
    );

    header(
        "Location: index.php?pedido_criado=" . $pedidoId
    );
    exit;
    } catch (InvalidArgumentException $erro) {
    $mensagemErro = $erro->getMessage();
    } catch (PDOException $erro) {
    error_log($erro->getMessage());
    $mensagemErro = "Erro ao salvar o pedido no banco de dados.";
    } catch (RuntimeException $erro) {
    $mensagemErro = $erro->getMessage();
    } catch (Throwable $erro) {
    error_log($erro->getMessage());
    $mensagemErro = "Não foi possível cadastrar o pedido.";
    }
}

$dados = $controller->formularioCadastro();

$clientes = $dados["clientes"];
$unidades = $dados["unidades"];
$produtos = $dados["produtos"];
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastrar Pedido</title>
    <?php if ($mensagemErro !== "") { ?>
    <p><?= htmlspecialchars($mensagemErro) ?></p>
    <?php } ?>
</head>
<body>
    <h1>Cadastrar Pedido</h1>
    <form method="POST">
        <label>Cliente:</label><br>
        <select name="cliente_id" required>
            <?php foreach ($clientes as $cliente) { ?>
                <option value="<?= $cliente["id"] ?>">
                    <?= htmlspecialchars($cliente["nome"]) ?>
                </option>
            <?php } ?>
        </select><br><br>

         <label>Unidade:</label><br>
        <select name="unidade_id" required>
            <?php foreach ($unidades as $unidade) { ?>
                <option value="<?= $unidade["id"] ?>">
                    <?= htmlspecialchars($unidade["nome"]) ?>
                </option>
            <?php } ?>
        </select><br><br>

        <label>Canal do pedido:</label><br>
        <select name="canal_pedido" required>
            <option value="APP">APP</option>
            <option value="WEB">WEB</option>
            <option value="TOTEM">TOTEM</option>
            <option value="BALCAO">BALCÃO</option>
            <option value="PICKUP">PICKUP</option>
        </select><br><br>

        <label>Produto:</label><br>
        <select name="produto_id" required>
            <?php foreach ($produtos as $produto) { ?>
                <option value="<?= $produto["id"] ?>">
                    <?= htmlspecialchars($produto["nome"]) ?>
                    — R$ <?= number_format($produto["preco"], 2, ",", ".") ?>
                </option>
            <?php } ?>
        </select><br><br>

        <label>Quantidade:</label><br>
        <input type="number" name="quantidade" min="1" required><br><br>

        <label>Forma de pagamento:</label><br>
        <select name="forma_pagamento" required>
            <option value="DEBITO">Débito</option>
            <option value="CREDITO">Crédito</option>
            <option value="PIX">PIX</option>
        </select><br><br>

        <button type="submit">Cadastrar</button>
        <a href="index.php">Cancelar</a>
    </form>
</body>
</html>
