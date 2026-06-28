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

    private const STATUS_INICIAL = "AGUARDANDO PAGAMENTO";

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
}