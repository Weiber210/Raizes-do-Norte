<?php
// Bloqueio de Login
require_once '../auth/verificar.php';
// Conectar ao Banco de Dados
require_once "../../config/database.php";
// Permissão
autorizarPerfis(["Administrador","Gerente","Atendente"]);

require_once "../../app/Repositories/PedidoRepository.php";
require_once "../../app/Services/PedidoService.php";
require_once "../../app/Controllers/PedidoController.php";

$repository = new PedidoRepository($pdo);
$service = new PedidoService($repository);
$controller = new PedidoController($service);

$pagamentos = $controller->listarPagamentos();

$tituloPagina = "Pagamentos";
require dirname(__DIR__) . "/componentes/cabecalho.php";
?>
    <h1 class="titulo-pagina mb-4">Pagamentos</h1>

    <div class="table-responsive">
    <table class="table table-striped table-hover align-middle">
        <tr>
            <th>ID</th>
            <th>Pedido</th>
            <th>Cliente</th>
            <th>Status</th>
            <th>Valor</th>
            <th>Forma</th>
            <th>Data</th>
        </tr>

        <?php foreach ($pagamentos as $pagamento) { ?>
        <tr>
            <td><?= (int) $pagamento["id"] ?></td>
            <td><?= (int) $pagamento["pedido_id"] ?></td>
            <td><?= htmlspecialchars($pagamento["cliente"], ENT_QUOTES, "UTF-8") ?></td>
            <td>
                <span class="badge text-bg-secondary">
                    <?= htmlspecialchars($pagamento["status"], ENT_QUOTES, "UTF-8") ?>
                </span>
            </td>
            <td>R$ <?= number_format((float) $pagamento["valor"], 2, ",", ".") ?></td>
            <td><?= htmlspecialchars($pagamento["forma_pagamento"], ENT_QUOTES, "UTF-8") ?></td>
            <td><?= htmlspecialchars($pagamento["data_pagamento"], ENT_QUOTES, "UTF-8") ?></td>
        </tr>
        <?php } ?>
    </table>
    </div>
<?php require dirname(__DIR__) . "/componentes/rodape.php"; ?>