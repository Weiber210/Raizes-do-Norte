<?php
$perfilMenu = $_SESSION["perfil"] ?? "";
?>
<nav class="navbar navbar-expand-lg navbar-dark">
    <div class="container">
        <a class="navbar-brand" href="/Raizes-do-Norte/public/dashboard.php">Raízes do Nordeste</a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#menuPrincipal"><span class="navbar-toggler-icon"></span></button>

        <div class="collapse navbar-collapse" id="menuPrincipal">
            <ul class="navbar-nav me-auto">
                <li class="nav-item"><a class="nav-link" href="/Raizes-do-Norte/public/pedidos/index.php">Pedidos</a></li>

                <?php if (in_array($perfilMenu, ["Administrador", "Gerente"], true)) { ?>
                <li class="nav-item"><a class="nav-link" href="/Raizes-do-Norte/public/produtos/index.php">Produtos</a></li>
                <li class="nav-item"><a class="nav-link" href="/Raizes-do-Norte/public/unidades/index.php">Unidades</a></li>
                <?php } ?>

                <?php if ($perfilMenu === "Administrador") { ?>
                <li class="nav-item"><a class="nav-link" href="/Raizes-do-Norte/public/usuarios/index.php">Usuários</a></li>
                <?php } ?>

                <li class="nav-item"><a class="nav-link" href="/Raizes-do-Norte/public/swagger/index.php">Swagger</a></li>
            </ul>

            <span class="navbar-text me-3"><?= htmlspecialchars($_SESSION["usuario"], ENT_QUOTES, "UTF-8") ?></span>

            <a class="btn btn-outline-light" href="/Raizes-do-Norte/public/logout.php">Sair</a>
        </div>
    </div>
</nav>