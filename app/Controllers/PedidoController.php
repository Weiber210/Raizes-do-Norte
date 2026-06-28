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

    public function processarPagamento(
    array $dados,
    int $usuarioResponsavelId
    ): string {
    // Valida a entrada do mock e encaminha ao serviço.
    if (
        !isset($dados["pedido_id"]) ||
        !isset($dados["resultado"])
    ) {
        throw new InvalidArgumentException(
            "Dados do pagamento não informados."
        );
    }

    $pedidoId = filter_var(
        $dados["pedido_id"],
        FILTER_VALIDATE_INT
    );

    if ($pedidoId === false) {
        throw new InvalidArgumentException(
            "Identificador do pedido inválido."
        );
    }

    return $this->service->processarPagamento(
        $pedidoId,
        $dados["resultado"],
        $usuarioResponsavelId
    );
    }

    public function formularioEdicao(int $pedidoId): array
    {
    return $this->service->obterPedidoParaEdicao($pedidoId);
    }

    public function atualizarStatus(array $dados, int $usuarioResponsavelId, string $perfil): string
    {
    // Valida a entrada e encaminha a atualização ao serviço.
    if (!isset($dados["pedido_id"]) || !isset($dados["novo_status"])) {
        throw new InvalidArgumentException("Dados da atualização não informados.");
    }

    $pedidoId = filter_var($dados["pedido_id"], FILTER_VALIDATE_INT);

    if ($pedidoId === false) {
        throw new InvalidArgumentException("Identificador do pedido inválido.");
    }

    return $this->service->atualizarStatus(
        $pedidoId,
        $dados["novo_status"],
        $usuarioResponsavelId,
        $perfil
    );
    }

    public function listarCardapio(): array
    {
    return $this->service->listarCardapio();
    }

    public function listarEstoque(array $filtros): array
    {
    $unidadeId = null;

    if (
        isset($filtros["unidadeId"]) &&
        $filtros["unidadeId"] !== ""
    ) {
        $unidadeId = filter_var(
            $filtros["unidadeId"],
            FILTER_VALIDATE_INT
        );

        if ($unidadeId === false) {
            throw new InvalidArgumentException(
                "Unidade inválida."
            );
        }
    }

    return $this->service->listarEstoque($unidadeId);
    }

    public function consultarFidelidade(int $usuarioId): array
    {
    return $this->service->consultarFidelidade($usuarioId);
    }

    public function listarPagamentos(): array
    {
    return $this->service->listarPagamentos();
    }

    public function listarAuditoria(): array
    {
    return $this->service->listarAuditoria();
    }

    public function indicadoresDashboard(): array
    {
    return $this->service->obterIndicadoresDashboard();
    }

    public function cancelar(
    int $pedidoId,
    int $usuarioResponsavelId
    ): string {
    return $this->service->cancelarPedido(
        $pedidoId,
        $usuarioResponsavelId
    );
    }
}