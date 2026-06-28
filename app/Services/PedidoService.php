<?php
class PedidoService{

    private const CANAIS = [
        "APP",
        "WEB",
        "TOTEM",
        "BALCAO",
        "PICKUP"
    ];

    private const FORMAS_PAGAMENTO = [
    "DEBITO",
    "CREDITO",
    "PIX"
    ];

    private const STATUS_INICIAL = "AGUARDANDO_PAGAMENTO";

    private const RESULTADOS_PAGAMENTO = [
    "APROVADO",
    "RECUSADO"
    ];

    private const TRANSICOES_STATUS = [
    "EM_PREPARO" => "PRONTO",
    "PRONTO" => "ENTREGUE"
    ];

    private const PERFIS_POR_STATUS = [
    "PRONTO" => [
        "Administrador",
        "Gerente",
        "Cozinha"
    ],
    "ENTREGUE" => [
        "Administrador",
        "Gerente",
        "Atendente"
    ]
    ];

    public function __construct(private PedidoRepository $repository)
    {}
    

    public function listar(?string $canal = null): array
    {
    // Valida o canal informado
    if ($canal !== null && $canal !== "") {
        $canal = strtoupper(trim($canal));

    if (!in_array($canal, self::CANAIS, true)) {
            throw new InvalidArgumentException("Canal de pedido inválido.");
        }
    }
    return $this->repository->listar($canal);
    }
    // Dados formulário
    public function obterDadosCadastro(): array
    {
    
    return [
            "clientes" => $this->repository->listarClientes(),
            "unidades" => $this->repository->listarUnidadesDisponiveis(),
            "produtos" => $this->repository->listarProdutosDisponiveis()
    ];
    }
    public function cadastrar(
    int $clienteId,
    int $unidadeId,
    int $produtoId,
    int $quantidade,
    string $canal,
    string $formaPagamento,
    int $usuarioResponsavelId
    ): int {
    // Valida os dados
    $canal = strtoupper(trim($canal));
    $formaPagamento = strtoupper(trim($formaPagamento));

    if (
        $clienteId <= 0 ||
        $unidadeId <= 0 ||
        $produtoId <= 0 ||
        $usuarioResponsavelId <= 0
    ) {
        throw new InvalidArgumentException("Dados do pedido inválidos.");
    }

    if ($quantidade <= 0) {
        throw new InvalidArgumentException("A quantidade deve ser maior que zero.");
    }

    if (!in_array($canal, self::CANAIS, true)) {
        throw new InvalidArgumentException("Canal de pedido inválido.");
    }

    if (!in_array($formaPagamento, self::FORMAS_PAGAMENTO, true)) {
        throw new InvalidArgumentException("Forma de pagamento inválida.");
    }

    if (!$this->repository->clienteExiste($clienteId)) {
        throw new InvalidArgumentException("Cliente não encontrado.");
    }

    $this->repository->iniciarTransacao();

    try {
    $estoque = $this->repository->buscarProdutoNoEstoque(
        $produtoId,
        $unidadeId
    );

    if ($estoque === false) {
        throw new RuntimeException(
            "Produto indisponível na unidade selecionada."
        );
    }

    if ((int) $estoque["quantidade"] < $quantidade) {
        throw new RuntimeException("Estoque insuficiente.");
    }

    $valorUnitario = number_format(
        (float) $estoque["preco"],
        2,
        ".",
        ""
    );

    $subtotal = number_format(
        (float) $valorUnitario * $quantidade,
        2,
        ".",
        ""
    );

    $pedidoId = $this->repository->criarPedido(
        $clienteId,
        $unidadeId,
        $canal,
        self::STATUS_INICIAL,
        $formaPagamento,
        $subtotal
    );

    $this->repository->adicionarItem(
        $pedidoId,
        $produtoId,
        $quantidade,
        $valorUnitario,
        $subtotal
    );

    $estoqueAtualizado = $this->repository->baixarEstoque(
        (int) $estoque["estoque_id"],
        $quantidade
    );

    if (!$estoqueAtualizado) {
        throw new RuntimeException("Não foi possível atualizar o estoque.");
    }

    $this->repository->registrarAuditoria(
        $usuarioResponsavelId,
        "CRIAR_PEDIDO",
        "Pedido número " . $pedidoId . " criado."
    );

    $this->repository->confirmarTransacao();

    return $pedidoId;
    } catch (Throwable $erro) {
    $this->repository->desfazerTransacao();

    throw $erro;
    }
    }

    public function processarPagamento(
    int $pedidoId,
    string $resultado,
    int $usuarioResponsavelId
    ): string {
    // Processa a resposta simulada do gateway de pagamento.
    $resultado = strtoupper(trim($resultado));

    if ($pedidoId <= 0 || $usuarioResponsavelId <= 0) {
        throw new InvalidArgumentException(
            "Dados do pagamento inválidos."
        );
    }

    if (
        !in_array(
            $resultado,
            self::RESULTADOS_PAGAMENTO,
            true
        )
    ) {
        throw new InvalidArgumentException(
            "Resultado do pagamento inválido."
        );
    }

    $this->repository->iniciarTransacao();

    try {
        $pedido = $this->repository->buscarPedidoParaPagamento(
            $pedidoId
        );

        if ($pedido === false) {
            throw new RuntimeException("Pedido não encontrado.");
        }

        if ($pedido["status"] !== self::STATUS_INICIAL) {
            throw new RuntimeException(
                "O pedido não está aguardando pagamento."
            );
        }

        $this->repository->registrarPagamento(
            $pedidoId,
            $resultado,
            $pedido["valor_total"],
            $pedido["forma_pagamento"]
        );

        if ($resultado === "APROVADO") {
            $novoStatus = "EM_PREPARO";
        } else {
            $novoStatus = "CANCELADO";

            $estoqueDevolvido =
                $this->repository->devolverEstoqueDoPedido(
                    $pedidoId
                );

            if (!$estoqueDevolvido) {
                throw new RuntimeException(
                    "Não foi possível devolver o estoque."
                );
            }
        }

        $statusAtualizado =
            $this->repository->atualizarStatusPedido(
                $pedidoId,
                $novoStatus
            );

        if (!$statusAtualizado) {
            throw new RuntimeException(
                "Não foi possível atualizar o pedido."
            );
        }

        $this->repository->registrarAuditoria(
            $usuarioResponsavelId,
            "PROCESSAR_PAGAMENTO",
            "Pagamento do pedido número "
                . $pedidoId
                . ": "
                . $resultado
                . "."
        );

        $this->repository->confirmarTransacao();

        return $novoStatus;
    } catch (Throwable $erro) {
        $this->repository->desfazerTransacao();

        throw $erro;
    }
    }

    public function obterPedidoParaEdicao(int $pedidoId): array
    {
    if ($pedidoId <= 0) {
        throw new InvalidArgumentException("Pedido inválido.");
    }

    $pedido = $this->repository->buscarPedidoPorId($pedidoId);

    if ($pedido === false) {
        throw new RuntimeException("Pedido não encontrado.");
    }

    $pedido["proximo_status"] = self::TRANSICOES_STATUS[$pedido["status"]] ?? null;

    return $pedido;
    }

    public function atualizarStatus(
    int $pedidoId,
    string $novoStatus,
    int $usuarioResponsavelId,
    string $perfil
    ): string {
    // Valida a transição e atualiza o status do pedido.
    $novoStatus = strtoupper(trim($novoStatus));
    $perfil = trim($perfil);

    if ($pedidoId <= 0 || $usuarioResponsavelId <= 0) {
        throw new InvalidArgumentException("Dados do pedido inválidos.");
    }

    $this->repository->iniciarTransacao();

    try {
        $pedido = $this->repository->buscarPedidoParaAtualizacao($pedidoId);

        if ($pedido === false) {
            throw new RuntimeException("Pedido não encontrado.");
        }

        $statusAtual = $pedido["status"];
        $statusPermitido = self::TRANSICOES_STATUS[$statusAtual] ?? null;

        if ($statusPermitido === null) {
            throw new RuntimeException("O pedido não permite nova atualização.");
        }

        if ($novoStatus !== $statusPermitido) {
            throw new RuntimeException("Transição de status inválida.");
        }

        $perfisPermitidos = self::PERFIS_POR_STATUS[$novoStatus] ?? [];

        if (!in_array($perfil, $perfisPermitidos, true)) {
            throw new RuntimeException("Perfil sem permissão para atualizar este status.");
        }

        $atualizado = $this->repository->atualizarStatusPedido(
            $pedidoId,
            $novoStatus
        );

        if (!$atualizado) {
            throw new RuntimeException("Não foi possível atualizar o status.");
        }

        $this->repository->registrarAuditoria(
            $usuarioResponsavelId,
            "ATUALIZAR_STATUS_PEDIDO",
            "Pedido número " . $pedidoId . " atualizado para " . $novoStatus . "."
        );

        $this->repository->confirmarTransacao();

        return $novoStatus;
    } catch (Throwable $erro) {
        $this->repository->desfazerTransacao();

        throw $erro;
    }
    }

    public function listarCardapio(): array
    {
    return $this->repository->listarProdutosDisponiveis();
    }

    public function listarEstoque(?int $unidadeId = null): array
    {
    if ($unidadeId !== null && $unidadeId <= 0) {
        throw new InvalidArgumentException("Unidade inválida.");
    }

    return $this->repository->listarEstoque($unidadeId);
    }

    public function consultarFidelidade(int $usuarioId): array
    {
    if ($usuarioId <= 0) {
        throw new InvalidArgumentException("Cliente inválido.");
    }

    $fidelidade = $this->repository->consultarFidelidade(
        $usuarioId
    );

    if ($fidelidade === false) {
        throw new RuntimeException("Cliente não encontrado.");
    }

    return $fidelidade;
    }

    public function listarPagamentos(): array
    {
    return $this->repository->listarPagamentos();
    }

    public function listarAuditoria(): array
    {
    return $this->repository->listarAuditoria();
    }

    public function obterIndicadoresDashboard(): array
    {
    return $this->repository->obterIndicadoresDashboard();
    }

    public function cancelarPedido(
    int $pedidoId,
    int $usuarioResponsavelId
    ): string {
    if ($pedidoId <= 0 || $usuarioResponsavelId <= 0) {
        throw new InvalidArgumentException("Dados inválidos.");
    }

    $this->repository->iniciarTransacao();

    try {
        $pedido = $this->repository->buscarPedidoParaAtualizacao(
            $pedidoId
        );

        if ($pedido === false) {
            throw new RuntimeException("Pedido não encontrado.");
        }

        if ($pedido["status"] !== self::STATUS_INICIAL) {
            throw new RuntimeException(
                "Somente pedidos aguardando pagamento podem ser cancelados."
            );
        }

        if (!$this->repository->devolverEstoqueDoPedido($pedidoId)) {
            throw new RuntimeException(
                "Não foi possível devolver o estoque."
            );
        }

        if (
            !$this->repository->atualizarStatusPedido(
                $pedidoId,
                "CANCELADO"
            )
        ) {
            throw new RuntimeException(
                "Não foi possível cancelar o pedido."
            );
        }

        $this->repository->registrarAuditoria(
            $usuarioResponsavelId,
            "CANCELAR_PEDIDO",
            "Pedido número " . $pedidoId . " cancelado."
        );

        $this->repository->confirmarTransacao();

        return "CANCELADO";
    } catch (Throwable $erro) {
        $this->repository->desfazerTransacao();

        throw $erro;
    }
    }

}