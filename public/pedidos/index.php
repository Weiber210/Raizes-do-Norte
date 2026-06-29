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

<?php
$tituloPagina = "Pedidos";
require dirname(__DIR__) . "/componentes/cabecalho.php";
?>
    <h1 class="titulo-pagina mb-4">Pedidos</h1>

        <?php if (isset($_GET["pedido_criado"])) { ?>
        <div class="alert alert-success">
        Pedido número <?= (int) $_GET["pedido_criado"] ?> cadastrado com sucesso.
        </div>
        <?php } ?>

        <?php if (isset($_GET["pagamento"])) { ?>
        <div class="alert alert-info">
        Pagamento do pedido <?= (int) ($_GET["pedido_id"] ?? 0) ?>:
        <?= htmlspecialchars($_GET["pagamento"], ENT_QUOTES, "UTF-8") ?>.
        </div>
        <?php } ?>

        <?php if (isset($_GET["status_atualizado"])) { ?>
        <div class="alert alert-success">
        Pedido número <?= (int) $_GET["status_atualizado"] ?> atualizado com sucesso.
        </div>
        <?php } ?>

        <?php if (isset($_GET["erro_pagamento"])) { ?>
        <div class="alert alert-danger">
        <?= htmlspecialchars($_GET["erro_pagamento"], ENT_QUOTES, "UTF-8") ?>
        </div>
        <?php } ?>
    
        <a class="btn btn-primary" href="cadastrar.php">Novo Pedido</a>
        <a class="btn btn-secondary" href="../dashboard.php">Voltar</a>

        <br><br>

        <form method="GET">
            <select class="form-select" name="canalPedido">
                <option value="">Todos os canais</option>
                <option value="APP">APP</option>
                <option value="WEB">WEB</option>
                <option value="TOTEM">TOTEM</option>
                <option value="BALCAO">BALCÃO</option>
                <option value="PICKUP">PICKUP</option>
            </select>

            <button class="btn btn-primary mt-2" type="submit">Filtrar</button>
        </form>

    <div class="table-responsive mt-4">
    <table class="table table-striped table-hover align-middle">
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
                <td><span class="badge text-bg-secondary"><?= htmlspecialchars(str_replace("_", " ", $pedido["status"]), ENT_QUOTES, "UTF-8") ?></span></td>
                <td><?= $pedido['forma_pagamento'] ?></td>
                <td>R$ <?= number_format($pedido['valor_total'], 2, ',', '.') ?></td>
                <td class="actions">
                    <div class="d-flex flex-wrap gap-1">
                        <?php if (in_array($pedido["status"], ["EM_PREPARO", "PRONTO"], true)) { ?>
                        <a class="btn btn-sm btn-warning" href="editar.php?id=<?= (int) $pedido["id"] ?>">Atualizar status</a>
                        <?php } ?>

                        <?php if ($pedido["status"] === "AGUARDANDO_PAGAMENTO") { ?>
                        <form class="d-inline" method="POST" action="cancelar.php">
                        <input type="hidden" name="pedido_id" value="<?= (int) $pedido["id"] ?>">
                        <button class="btn btn-sm btn-secondary" type="submit" onclick="return confirm(&quot;Deseja cancelar este pedido?&quot;);">Cancelar</button>
                        </form>

                        <form class="d-inline" method="POST" action="../pagamentos/processar.php">
                        <input type="hidden" name="pedido_id" value="<?= (int) $pedido["id"] ?>">
                        <button class="btn btn-sm btn-success" type="submit" name="resultado" value="APROVADO">Aprovar</button>
                        <button class="btn btn-sm btn-danger" type="submit" name="resultado" value="RECUSADO">Recusar</button>
                        </form>
                        <?php } ?>
                    </div>
                </td>        </tr>
        <?php } ?>
    </table>
    </div>
<?php require dirname(__DIR__) . "/componentes/rodape.php"; ?>