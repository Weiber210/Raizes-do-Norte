<?php
// Bloqueio de Login
require_once 'auth/verificar.php';


header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
header("Expires: Sat, 26 Jul 1997 05:00:00 GMT");

?>

<?php
$tituloPagina = "Dashboard";
require __DIR__ . "/componentes/cabecalho.php";
?>
    <h1>Raízes do Norte</h1>
    <p>Bem-vindo, <?php echo $_SESSION["usuario"]; ?>!</p>
    <p>Perfil: <?php echo $_SESSION["perfil"]; ?></p>

    <hr>



<?php require __DIR__ . "/componentes/rodape.php"; ?>