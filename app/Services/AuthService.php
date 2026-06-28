<?php

class AuthService{

    public function __construct(
        private AuthRepository $repository,
        private Jwt $jwt
    ) {
    }

    public function autenticar(string $email, string $senha): array
    {
    // Valida as credenciais
    $email = trim($email);

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        throw new InvalidArgumentException("E-mail inválido.");
    }

    if (trim($senha) === "") {
        throw new InvalidArgumentException("A senha é obrigatória.");
    }

    $usuario = $this->repository->buscarPorEmail($email);

    if (
        $usuario === false ||
        !password_verify($senha, $usuario["senha"])
    ) {
        throw new RuntimeException("E-mail ou senha inválidos.");
    }

    $token = $this->jwt->gerar([
        "sub" => (int) $usuario["id"],
        "nome" => $usuario["nome"],
        "perfil" => $usuario["perfil"]
    ]);

    return [
        "accessToken" => $token,
        "tokenType" => "Bearer",
        "expiresIn" => $this->jwt->obterExpiracao(),
        "usuario" => [
            "id" => (int) $usuario["id"],
            "nome" => $usuario["nome"],
            "perfil" => $usuario["perfil"]
        ]
    ];
    }
}