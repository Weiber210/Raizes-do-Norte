<?php
$caminhoLogs = dirname(__DIR__) . "/storage/logs";

if (!is_dir($caminhoLogs)) {
    mkdir($caminhoLogs, 0775, true);
}

ini_set("log_errors", "1");
ini_set("error_log", $caminhoLogs . "/app.log");