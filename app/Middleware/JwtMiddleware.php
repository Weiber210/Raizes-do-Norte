<?php
class JwtMiddleware{

    public function __construct(private Jwt $jwt)
    {}

    public function autenticar(): array
    {
    $autorizacao = $_SERVER["HTTP_AUTHORIZATION"] ?? "";

    if ($autorizacao === "" && function_exists("getallheaders")) {
        $cabecalhos = getallheaders();

        $autorizacao =
            $cabecalhos["Authorization"]
            ?? $cabecalhos["authorization"]
            ?? "";
    }

    if (!preg_match("/^Bearer\s+(.+)$/i", $autorizacao, $resultado)) {
        throw new RuntimeException("Token de acesso não informado.");
    }

    try {
        return $this->jwt->validar(trim($resultado[1]));
    } catch (Throwable $erro) {
        throw new RuntimeException("Token inválido.");
    }
    }

    public function autorizar(array $usuario, array $perfis): void
    {
    $perfil = $usuario["perfil"] ?? "";

    if (!in_array($perfil, $perfis, true)) {
        throw new DomainException(
            "Perfil sem permissão para realizar esta operação."
        );
    }
    }
}