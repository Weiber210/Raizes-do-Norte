<?php
// Bloqueio de Login
require_once '../auth/verificar.php';
//Conectar ao banco de dados
require_once '../../config/database.php';

require_once '../../app/Repositories/PedidoRepository.php';
require_once '../../app/Services/PedidoService.php';
require_once '../../app/Controllers/PedidoController.php';

$repository = new PedidoRepository($pdo);
$service = new PedidoService($repository);
$controller = new PedidoController($service);

$pedidos = $controller->listar($_GET);

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pedidos</title>
</head>
<body>
    <H1>Pedidos</H1>
    <?php if (isset($_GET["pedido_criado"])) { ?>
    <p>
        Pedido número
        <?= (int) $_GET["pedido_criado"] ?>
        cadastrado com sucesso.
    </p>
    <?php } ?>
    <?php if (isset($_GET["pagamento"])) { ?>
    <p>
        Pagamento do pedido
        <?= (int) ($_GET["pedido_id"] ?? 0) ?>:
        <?= htmlspecialchars($_GET["pagamento"]) ?>.
    </p>
    <?php } ?>

    <?php if (isset($_GET["erro_pagamento"])) { ?>
    <p>
        <?= htmlspecialchars($_GET["erro_pagamento"]) ?>
    </p>
    <?php } ?>
    
        <a href="cadastrar.php"><button>Novo Pedido</button></a>
        <a href="../dashboard.php"><button>Voltar</button></a>

        <br><br>

        <form method="GET">
            <select name="canalPedido">
                <option value="">Todos os canais</option>
                <option value="APP">APP</option>
                <option value="WEB">WEB</option>
                <option value="TOTEM">TOTEM</option>
                <option value="BALCAO">BALCÃO</option>
                <option value="PICKUP">PICKUP</option>
            </select>

            <button type="submit">Filtrar</button>
        </form>

    <table border="1" cellpadding="8">
        <tr>
            <th>ID</th>
            <th>Cliente</th>
            <th>Unidade</th>
            <th>Canal Pedido</th>
            <th>Status</th>
            <th>Forma de Pagamento</th>
            <th>Valor Total</th>
            <th>Ações</th>
        </tr>
        <?php foreach($pedidos as $pedido){ ?>
        <tr>
                <td><?= $pedido['id'] ?></td>
                <td><?= htmlspecialchars($pedido['cliente']) ?></td>
                <td><?= htmlspecialchars($pedido['unidade']) ?></td>
                <td><?= htmlspecialchars($pedido['canal_pedido']) ?></td>
                <td><?= $pedido['status'] ?></td>
                <td><?= $pedido['forma_pagamento'] ?></td>
                <td>R$ <?= number_format($pedido['valor_total'], 2, ',', '.') ?></td>
                <td class="actions">
                    <a href="editar.php?id=<?= $pedido['id'] ?>"><button>Editar</button></a>
                    <a onclick="return confirm('Tem certeza que deseja excluir este pedido?');" href="excluir.php?id=<?= $pedido['id'] ?>"><button>Excluir</button></a>
                    <?php if ($pedido["status"] === "AGUARDANDO_PAGAMENTO") { ?>
                    
                    <form method="POST" action="../pagamentos/processar.php" style="display: inline;">
                        <input type="hidden" name="pedido_id" value="<?= (int) $pedido["id"] ?>">

                        <button type="submit" name="resultado" value="APROVADO">Aprovar pagamento</button>
                        <button type="submit" name="resultado" value="RECUSADO">Recusar pagamento</button>

                    </form>
                    <?php } ?>
                </td>
        </tr>
            <?php } ?>
    </table>
</body>
</html>