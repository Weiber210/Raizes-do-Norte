<?php
// Bloqueio de Login
require_once 'auth/verificar.php';
// Conectar ao Banco de Dados
require_once "../config/database.php";

require_once "../app/Repositories/PedidoRepository.php";
require_once "../app/Services/PedidoService.php";
require_once "../app/Controllers/PedidoController.php";

$repository = new PedidoRepository($pdo);
$service = new PedidoService($repository);
$controller = new PedidoController($service);

$indicadores = $controller->indicadoresDashboard();

header("Cache-Control: no-store, no-cache, must-revalidate");
header("Pragma: no-cache");
?>

<?php
$tituloPagina = "Dashboard";
require __DIR__ . "/componentes/cabecalho.php";
?>
    <h1>Raízes do Nordeste</h1>
    <p>Bem-vindo, <?= htmlspecialchars($_SESSION["usuario"], ENT_QUOTES, "UTF-8")?>!</p>
    <div class="row g-3 mt-2">
    <div class="col-md-3">
        <div class="card">
            <div class="card-body">
                <h2 class="h6">Pedidos do dia</h2>
                <p class="display-6"><?= (int) $indicadores["pedidos_dia"] ?></p>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card">
            <div class="card-body">
                <h2 class="h6">Pedidos do mês</h2>
                <p class="display-6"><?= (int) $indicadores["pedidos_mes"] ?></p>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card">
            <div class="card-body">
                <h2 class="h6">Pedidos pendentes</h2>
                <p class="display-6"><?= (int) $indicadores["pedidos_pendentes"] ?></p>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card">
            <div class="card-body">
                <h2 class="h6">Faturamento</h2>
                <p class="h3">
                    R$ <?= number_format((float) $indicadores["faturamento"], 2, ",", ".") ?>
                </p>
            </div>
        </div>
    </div>
    </div>

<?php require __DIR__ . "/componentes/rodape.php"; ?>