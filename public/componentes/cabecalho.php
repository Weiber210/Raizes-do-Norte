<?php
require_once dirname(__DIR__, 2) . "/config/app.php";

$tituloPagina = $tituloPagina ?? "Raízes do Nordeste";
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title><?= htmlspecialchars($tituloPagina, ENT_QUOTES, "UTF-8") ?></title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="<?= htmlspecialchars(urlPublica("assets/css/estilo.css"), ENT_QUOTES, "UTF-8") ?>" rel="stylesheet">

    <script>
    window.addEventListener("pageshow", function (evento) {
        if (evento.persisted) {
            window.location.reload();
        }
    });
</script>
</head>
<body>
    <?php if (isset($_SESSION["usuario"])) { ?>
    <?php require __DIR__ . "/menu.php"; ?>
    <?php } ?>

    <main class="container py-4">
