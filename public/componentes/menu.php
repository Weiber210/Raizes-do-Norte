<?php

$perfilMenu = $_SESSION["perfil"] ?? "";
?>
<nav class="navbar navbar-expand-lg navbar-dark">
    <div class="container">
        <a class="navbar-brand" href="<?= htmlspecialchars(urlPublica("dashboard.php"), ENT_QUOTES, "UTF-8") ?>">Raízes do Nordeste</a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#menuPrincipal"><span class="navbar-toggler-icon"></span></button>

        <div class="collapse navbar-collapse" id="menuPrincipal">
            <ul class="navbar-nav me-auto">
                <li class="nav-item"><a class="nav-link" href="<?= htmlspecialchars(urlPublica("pedidos/index.php"), ENT_QUOTES, "UTF-8") ?>">Pedidos</a></li>
                <li class="nav-item"><a class="nav-link" href="<?= htmlspecialchars(urlPublica("estoque/index.php"), ENT_QUOTES, "UTF-8") ?>">Estoque</a></li>

                <?php if (in_array($perfilMenu, ["Administrador", "Gerente", "Atendente"], true)) { ?>
                <li class="nav-item"><a class="nav-link" href="<?= htmlspecialchars(urlPublica("pagamentos/index.php"), ENT_QUOTES, "UTF-8") ?>">Pagamentos</a></li>
                <li class="nav-item"><a class="nav-link" href="<?= htmlspecialchars(urlPublica("fidelidade/index.php"), ENT_QUOTES, "UTF-8") ?>">Fidelidade</a></li>
                <?php } ?>

                <?php if (in_array($perfilMenu, ["Administrador", "Gerente"], true)) { ?>
                <li class="nav-item"><a class="nav-link" href="<?= htmlspecialchars(urlPublica("produtos/index.php"), ENT_QUOTES, "UTF-8") ?>">Produtos</a></li>
                <li class="nav-item"><a class="nav-link" href="<?= htmlspecialchars(urlPublica("unidades/index.php"), ENT_QUOTES, "UTF-8") ?>">Unidades</a></li>
                <li class="nav-item"><a class="nav-link" href="<?= htmlspecialchars(urlPublica("auditoria/index.php"), ENT_QUOTES, "UTF-8") ?>">Auditoria</a></li>
                <?php } ?>

                <?php if ($perfilMenu === "Administrador") { ?>
                <li class="nav-item"><a class="nav-link" href="<?= htmlspecialchars(urlPublica("usuarios/index.php"), ENT_QUOTES, "UTF-8") ?>">Usuários</a></li>
                <li class="nav-item"><a class="nav-link" href="<?= htmlspecialchars(urlPublica("swagger/index.php"), ENT_QUOTES, "UTF-8") ?>">Swagger</a></li>
                <?php } ?>
            </ul>

            <span class="navbar-text me-3">
                <?= htmlspecialchars($_SESSION["usuario"], ENT_QUOTES, "UTF-8") ?>
            </span>

            <a class="btn btn-outline-light" href="<?= htmlspecialchars(urlPublica("logout.php"), ENT_QUOTES, "UTF-8") ?>">Sair</a>
        </div>
    </div>
</nav>
