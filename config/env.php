<?php
$linhas = file(__DIR__ . '/../.env');

foreach ($linhas as $linha) {
    $linha = trim($linha);
    if ($linha == '') {
        continue;
    }

    list($chave, $valor) = explode('=', $linha, 2);

    $_ENV[$chave] = $valor;
}