<?php

function urlPublica(string $caminho = ""): string
{
    $script = str_replace(
        "\\",
        "/",
        (string) ($_SERVER["SCRIPT_NAME"] ?? "")
    );

    $marcador = "/public/";
    $posicao = strpos($script, $marcador);

    if ($posicao !== false) {
        $base = substr(
            $script,
            0,
            $posicao + strlen("/public")
        );
    } else {
        $base = "";
    }

    if ($caminho === "") {
        return $base === "" ? "/" : $base . "/";
    }

    return $base . "/" . ltrim($caminho, "/");
}
