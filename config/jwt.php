<?php
require_once __DIR__ . "/env.php";

class Jwt{

    private string $segredo;
    private int $expiracao;

    public function __construct()
    {
    $this->segredo = $_ENV["JWT_SECRET"] ?? "";
    $this->expiracao = (int) ($_ENV["JWT_EXPIRACAO"] ?? 3600);

    if (strlen($this->segredo) < 32) {
        throw new RuntimeException("A chave JWT não foi configurada corretamente.");
    }
    }

    public function gerar(array $dados): string
    {
    $agora = time();

    $cabecalho = [
        "alg" => "HS256",
        "typ" => "JWT"
    ];

    $payload = array_merge($dados, [
        "iat" => $agora,
        "exp" => $agora + $this->expiracao
    ]);

    $cabecalhoCodificado = $this->codificar(
        json_encode($cabecalho)
    );

    $payloadCodificado = $this->codificar(
        json_encode($payload)
    );

    $conteudo = $cabecalhoCodificado . "." . $payloadCodificado;

    $assinatura = hash_hmac(
        "sha256",
        $conteudo,
        $this->segredo,
        true
    );

    return $conteudo . "." . $this->codificar($assinatura);
    }

    public function validar(string $token): array
    {
    $partes = explode(".", $token);

    if (count($partes) !== 3) {
        throw new RuntimeException("Token inválido.");
    }

    [$cabecalhoCodificado, $payloadCodificado, $assinatura] = $partes;

    $cabecalho = json_decode(
        $this->decodificar($cabecalhoCodificado),
        true
    );

    if (($cabecalho["alg"] ?? "") !== "HS256") {
        throw new RuntimeException("Algoritmo JWT inválido.");
    }

    $conteudo = $cabecalhoCodificado . "." . $payloadCodificado;

    $assinaturaEsperada = $this->codificar(
        hash_hmac(
            "sha256",
            $conteudo,
            $this->segredo,
            true
        )
    );

    if (!hash_equals($assinaturaEsperada, $assinatura)) {
        throw new RuntimeException("Assinatura JWT inválida.");
    }

    $payload = json_decode(
        $this->decodificar($payloadCodificado),
        true
    );

    if (!is_array($payload) || !isset($payload["exp"])) {
        throw new RuntimeException("Conteúdo JWT inválido.");
    }

    if ((int) $payload["exp"] <= time()) {
        throw new RuntimeException("Token expirado.");
    }

    return $payload;
    }

    public function obterExpiracao(): int
    {
    return $this->expiracao;
    }

    private function codificar(string $conteudo): string
    {
    return rtrim(
        strtr(base64_encode($conteudo), "+/", "-_"),
        "="
    );
    }

    private function decodificar(string $conteudo): string
    {
    $resto = strlen($conteudo) % 4;

    if ($resto > 0) {
        $conteudo .= str_repeat("=", 4 - $resto);
    }

    $resultado = base64_decode(
        strtr($conteudo, "-_", "+/"),
        true
    );

    if ($resultado === false) {
        throw new RuntimeException("Token malformado.");
    }

    return $resultado;
    }
}