<?php
// Recebe os filtros da requisição
class PedidoController{

    public function __construct(private PedidoService $service)
    {}
    

    public function listar(array $filtros): array
    {
    $canal = $filtros["canalPedido"] ?? null;

    return $this->service->listar($canal);
    }
    // Solicita dados do formulário
    public function formularioCadastro(): array
    {
    
    return $this->service->obterDadosCadastro();
    }
    public function cadastrar(
    array $dados,
    int $usuarioResponsavelId
    ): int {

    // Valida a entrada
    $camposObrigatorios = [
        "cliente_id",
        "unidade_id",
        "produto_id",
        "quantidade",
        "canal_pedido",
        "forma_pagamento"
    ];

    foreach ($camposObrigatorios as $campo) {
        if (
            !isset($dados[$campo]) ||
            trim((string) $dados[$campo]) === ""
        ) {
            throw new InvalidArgumentException(
                "O campo " . $campo . " é obrigatório."
            );
        }
    }

    $clienteId = filter_var(
        $dados["cliente_id"],
        FILTER_VALIDATE_INT
    );

    $unidadeId = filter_var(
        $dados["unidade_id"],
        FILTER_VALIDATE_INT
    );

    $produtoId = filter_var(
        $dados["produto_id"],
        FILTER_VALIDATE_INT
    );

    $quantidade = filter_var(
        $dados["quantidade"],
        FILTER_VALIDATE_INT
    );

    if (
        $clienteId === false ||
        $unidadeId === false ||
        $produtoId === false ||
        $quantidade === false
    ) {
        throw new InvalidArgumentException(
            "Os identificadores e a quantidade devem ser números inteiros."
        );
    }

    return $this->service->cadastrar(
        $clienteId,
        $unidadeId,
        $produtoId,
        $quantidade,
        $dados["canal_pedido"],
        $dados["forma_pagamento"],
        $usuarioResponsavelId
    );
    }
}