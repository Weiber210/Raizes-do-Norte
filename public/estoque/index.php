<?php
// Bloqueio de Login
require_once '../auth/verificar.php';
// Conectar ao Banco de Dados
require_once "../../config/database.php";
// Permissão
autorizarPerfis(["Administrador","Gerente","Atendente","Cozinha"]);

require_once "../../app/Repositories/PedidoRepository.php";
require_once "../../app/Services/PedidoService.php";
require_once "../../app/Controllers/PedidoController.php";

$repository = new PedidoRepository($pdo);
$service = new PedidoService($repository);
$controller = new PedidoController($service);

$estoques = $controller->listarEstoque($_GET);

$tituloPagina = "Estoque";
require dirname(__DIR__) . "/componentes/cabecalho.php";
?>
    <h1 class="titulo-pagina mb-4">Estoque</h1>

    <div class="table-responsive">
    <table class="table table-striped table-hover align-middle">
        <tr>
            <th>ID</th>
            <th>Produto</th>
            <th>Unidade</th>
            <th>Quantidade</th>
            <th>Última atualização</th>
        </tr>

        <?php foreach ($estoques as $estoque) { ?>
        <tr>
            <td><?= (int) $estoque["id"] ?></td>
            <td><?= htmlspecialchars($estoque["produto"], ENT_QUOTES, "UTF-8") ?></td>
            <td><?= htmlspecialchars($estoque["unidade"], ENT_QUOTES, "UTF-8") ?></td>
            <td><?= (int) $estoque["quantidade"] ?></td>
            <td><?= htmlspecialchars($estoque["ultima_atualizacao"], ENT_QUOTES, "UTF-8") ?></td>
        </tr>
        <?php } ?>
    </table>
    </div>
<?php require dirname(__DIR__) . "/componentes/rodape.php"; ?>