<?php
class AuthController{

    public function __construct(private AuthService $service)
    {
    }

    public function login(array $dados): array
    {
    // Valida a entrada
    if (!isset($dados["email"]) || !isset($dados["senha"])) {
        throw new InvalidArgumentException(
            "E-mail e senha são obrigatórios."
        );
    }

    return $this->service->autenticar(
        (string) $dados["email"],
        (string) $dados["senha"]
    );
    }
}