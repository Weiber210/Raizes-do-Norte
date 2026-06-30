<?php
require_once "../auth/verificar.php";

autorizarPerfis(["Administrador"]);

$arquivo = dirname(__DIR__, 2) . "/docs/openapi.yaml";

if (!is_file($arquivo)) {
    http_response_code(404);
    exit("Documentação OpenAPI não encontrada.");
}

header("Content-Type: application/yaml; charset=UTF-8");
header("Cache-Control: no-store");

readfile($arquivo);
