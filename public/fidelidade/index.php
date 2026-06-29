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

$dadosCadastro = $controller->formularioCadastro();
$clientes = $dadosCadastro["clientes"];

$fidelidade = null;
$mensagemErro = "";

if (isset($_GET["cliente_id"]) && $_GET["cliente_id"] !== "") {
    try {
        $clienteId = filter_var(
            $_GET["cliente_id"],
            FILTER_VALIDATE_INT
        );

        if ($clienteId === false) {
            throw new InvalidArgumentException("Cliente inválido.");
        }

        $fidelidade = $controller->consultarFidelidade(
            $clienteId
        );
    } catch (Throwable $erro) {
        $mensagemErro = $erro->getMessage();
    }
}

$tituloPagina = "Fidelidade";
require dirname(__DIR__) . "/componentes/cabecalho.php";
?>

<h1 class="titulo-pagina mb-4">Fidelidade</h1>

<form class="mb-4" method="GET">
    <label class="form-label">Cliente:</label>

    <select class="form-select" name="cliente_id" required>
        <option value="">Selecione</option>

        <?php foreach ($clientes as $cliente) { ?>
        <option value="<?= (int) $cliente["id"] ?>">
            <?= htmlspecialchars($cliente["nome"], ENT_QUOTES, "UTF-8") ?>
        </option>
        <?php } ?>
    </select>

    <button class="btn btn-primary mt-2" type="submit">Consultar</button>
</form>

<?php if ($mensagemErro !== "") { ?>
<div class="alert alert-danger">
    <?= htmlspecialchars($mensagemErro, ENT_QUOTES, "UTF-8") ?>
</div>
<?php } ?>

<?php if ($fidelidade !== null) { ?>
<div class="card">
    <div class="card-body">
        <h2 class="h5"><?= htmlspecialchars($fidelidade["nome"], ENT_QUOTES, "UTF-8") ?></h2>
        <p class="mb-0">Pontos: <?= (int) $fidelidade["pontos"] ?></p>
    </div>
</div>
<?php } ?>

<?php require dirname(__DIR__) . "/componentes/rodape.php"; ?>