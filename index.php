<?php
$caminhoBase = rtrim(
str_replace("\\", "/", dirname($_SERVER["SCRIPT_NAME"])),
"/"
);

$destino = $caminhoBase === ""
    ? "/public/"
    : $caminhoBase . "/public/";

header("Location: " . $destino);
exit;