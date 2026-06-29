<?php
// Bloqueio de Login
require_once '../auth/verificar.php';
//Conectar ao banco de dados
require_once '../../config/database.php';
// Permissão
autorizarPerfis(["Administrador", "Gerente"]);

require_once "../../app/Repositories/PedidoRepository.php";
require_once "../../app/Services/PedidoService.php";
require_once "../../app/Controllers/PedidoController.php";

$repository = new PedidoRepository($pdo);
$service = new PedidoService($repository);
$controller = new PedidoController($service);

$registros = $controller->listarAuditoria();

$tituloPagina = "Auditoria";
require dirname(__DIR__) . "/componentes/cabecalho.php";
?>
    <h1 class="titulo-pagina mb-4">Auditoria</h1>

    <div class="table-responsive">
    <table class="table table-striped table-hover align-middle">
        <tr>
            <th>ID</th>
            <th>Usuário</th>
            <th>Ação</th>
            <th>Descrição</th>
            <th>Data</th>
        </tr>

        <?php foreach ($registros as $registro) { ?>
        <tr>
            <td><?= (int) $registro["id"] ?></td>
            <td><?= htmlspecialchars($registro["usuario"], ENT_QUOTES, "UTF-8") ?></td>
            <td><?= htmlspecialchars($registro["acao"], ENT_QUOTES, "UTF-8") ?></td>
            <td><?= htmlspecialchars($registro["descricao"], ENT_QUOTES, "UTF-8") ?></td>
            <td><?= htmlspecialchars($registro["data_hora"], ENT_QUOTES, "UTF-8") ?></td>
        </tr>
        <?php } ?>
    </table>
    </div>
<?php require dirname(__DIR__) . "/componentes/rodape.php"; ?>