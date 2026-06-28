<?php
class PedidoRepository{
    
    public function __construct(private PDO $pdo)
    {}
    public function listar(?string $canal = null): array
    {
     $sql = "
        SELECT
            p.id,
            u.nome AS cliente,
            un.nome AS unidade,
            p.canal_pedido,
            p.status,
            p.forma_pagamento,
            p.valor_total
        FROM pedidos p
        INNER JOIN usuarios u ON u.id = p.cliente_id
        INNER JOIN unidades un ON un.id = p.unidade_id
    ";
        
    if ($canal !== null && $canal !== "") {
        $sql .= " WHERE p.canal_pedido = :canal";
        }

        $sql .= " ORDER BY p.id";

        $stmt = $this->pdo->prepare($sql);

        if ($canal !== null && $canal !== "") {
            $stmt->bindValue(":canal", $canal, PDO::PARAM_STR);
        }

        $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_ASSOC);    
    }
    public function listarClientes(): array
    {
    $sql = "
        SELECT id, nome
        FROM usuarios
        WHERE perfil = :perfil
        ORDER BY nome
    ";

    $stmt = $this->pdo->prepare($sql);
    $stmt->bindValue(":perfil", "Cliente", PDO::PARAM_STR);
    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function listarUnidadesDisponiveis(): array
    {
    $sql = "
        SELECT DISTINCT u.id, u.nome
        FROM unidades u
        INNER JOIN estoque e ON e.unidade_id = u.id
        WHERE u.ativa = true
        AND e.quantidade > 0
        ORDER BY u.nome
    ";

    $stmt = $this->pdo->query($sql);

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function listarProdutosDisponiveis(): array
    {
    $sql = "
        SELECT DISTINCT p.id, p.nome, p.preco
        FROM produtos p
        INNER JOIN estoque e ON e.produto_id = p.id
        WHERE p.ativo = true
        AND e.quantidade > 0
        ORDER BY p.nome
    ";

    $stmt = $this->pdo->query($sql);

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Transação do pedido
    public function iniciarTransacao(): void
    {
    $this->pdo->beginTransaction();
    }

    public function confirmarTransacao(): void
    {
    if ($this->pdo->inTransaction()) {
        $this->pdo->commit();
        }
    }

    public function desfazerTransacao(): void
    {
    if ($this->pdo->inTransaction()) {
        $this->pdo->rollBack();
        }
    }

    public function clienteExiste(int $clienteId): bool
    {
    $sql = "
        SELECT COUNT(*)
        FROM usuarios
        WHERE id = :id
        AND perfil = :perfil
    ";

    $stmt = $this->pdo->prepare($sql);
    $stmt->bindValue(":id", $clienteId, PDO::PARAM_INT);
    $stmt->bindValue(":perfil", "Cliente", PDO::PARAM_STR);
    $stmt->execute();

    return (int) $stmt->fetchColumn() > 0;
    }

    public function buscarProdutoNoEstoque(
    int $produtoId,
    int $unidadeId
    ): array|false{
    $sql = "
        SELECT
            e.id AS estoque_id,
            e.quantidade,
            p.preco
        FROM estoque e
        INNER JOIN produtos p ON p.id = e.produto_id
        INNER JOIN unidades u ON u.id = e.unidade_id
        WHERE e.produto_id = :produto_id
        AND e.unidade_id = :unidade_id
        AND p.ativo = true
        AND u.ativa = true
        FOR UPDATE OF e
    ";

    $stmt = $this->pdo->prepare($sql);
    $stmt->bindValue(":produto_id", $produtoId, PDO::PARAM_INT);
    $stmt->bindValue(":unidade_id", $unidadeId, PDO::PARAM_INT);
    $stmt->execute();

    return $stmt->fetch(PDO::FETCH_ASSOC);
    } 

    public function criarPedido(
    int $clienteId,
    int $unidadeId,
    string $canal,
    string $status,
    string $formaPagamento,
    string $valorTotal
    ): int {
    $sql = "
        INSERT INTO pedidos (
            cliente_id,
            unidade_id,
            canal_pedido,
            status,
            forma_pagamento,
            valor_total
        )
        VALUES (
            :cliente_id,
            :unidade_id,
            :canal_pedido,
            :status,
            :forma_pagamento,
            :valor_total
        )
        RETURNING id
    ";

    $stmt = $this->pdo->prepare($sql);
    $stmt->bindValue(":cliente_id", $clienteId, PDO::PARAM_INT);
    $stmt->bindValue(":unidade_id", $unidadeId, PDO::PARAM_INT);
    $stmt->bindValue(":canal_pedido", $canal, PDO::PARAM_STR);
    $stmt->bindValue(":status", $status, PDO::PARAM_STR);
    $stmt->bindValue(":forma_pagamento", $formaPagamento, PDO::PARAM_STR);
    $stmt->bindValue(":valor_total", $valorTotal, PDO::PARAM_STR);
    $stmt->execute();

    return (int) $stmt->fetchColumn();
    }

    public function adicionarItem(
    int $pedidoId,
    int $produtoId,
    int $quantidade,
    string $valorUnitario,
    string $subtotal
    ): void {
    $sql = "
        INSERT INTO itens_pedido (
            pedido_id,
            produto_id,
            quantidade,
            valor_unitario,
            subtotal
        )
        VALUES (
            :pedido_id,
            :produto_id,
            :quantidade,
            :valor_unitario,
            :subtotal
        )
    ";

    $stmt = $this->pdo->prepare($sql);
    $stmt->bindValue(":pedido_id", $pedidoId, PDO::PARAM_INT);
    $stmt->bindValue(":produto_id", $produtoId, PDO::PARAM_INT);
    $stmt->bindValue(":quantidade", $quantidade, PDO::PARAM_INT);
    $stmt->bindValue(":valor_unitario", $valorUnitario, PDO::PARAM_STR);
    $stmt->bindValue(":subtotal", $subtotal, PDO::PARAM_STR);
    $stmt->execute();
    }
    public function baixarEstoque(int $estoqueId, int $quantidade): bool
    {
    $sql = "
        UPDATE estoque
        SET quantidade = quantidade - :quantidade,
            ultima_atualizacao = CURRENT_TIMESTAMP
        WHERE id = :estoque_id
        AND quantidade >= :quantidade
    ";

    $stmt = $this->pdo->prepare($sql);
    $stmt->bindValue(":quantidade", $quantidade, PDO::PARAM_INT);
    $stmt->bindValue(":estoque_id", $estoqueId, PDO::PARAM_INT);
    $stmt->execute();

    return $stmt->rowCount() === 1;
    }
    
    public function registrarAuditoria(
    int $usuarioId,
    string $acao,
    string $descricao
    ): void {
    $sql = "
        INSERT INTO auditoria (
            usuario_id,
            acao,
            descricao
        )
        VALUES (
            :usuario_id,
            :acao,
            :descricao
        )
    ";

    $stmt = $this->pdo->prepare($sql);
    $stmt->bindValue(":usuario_id", $usuarioId, PDO::PARAM_INT);
    $stmt->bindValue(":acao", $acao, PDO::PARAM_STR);
    $stmt->bindValue(":descricao", $descricao, PDO::PARAM_STR);
    $stmt->execute();
    }
    
    public function buscarPedidoParaPagamento(int $pedidoId): array|false
    {
    $sql = "
        SELECT id, status, valor_total, forma_pagamento
        FROM pedidos
        WHERE id = :pedido_id
        FOR UPDATE
    ";

    $stmt = $this->pdo->prepare($sql);
    $stmt->bindValue(":pedido_id", $pedidoId, PDO::PARAM_INT);
    $stmt->execute();

    return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    public function registrarPagamento(
    int $pedidoId,
    string $status,
    string $valor,
    string $formaPagamento
    ): void {
    $sql = "
        INSERT INTO pagamentos (
            pedido_id,
            status,
            valor,
            forma_pagamento
        )
        VALUES (
            :pedido_id,
            :status,
            :valor,
            :forma_pagamento
        )
    ";

    $stmt = $this->pdo->prepare($sql);
    $stmt->bindValue(":pedido_id", $pedidoId, PDO::PARAM_INT);
    $stmt->bindValue(":status", $status, PDO::PARAM_STR);
    $stmt->bindValue(":valor", $valor, PDO::PARAM_STR);
    $stmt->bindValue(
        ":forma_pagamento",
        $formaPagamento,
        PDO::PARAM_STR
    );
    $stmt->execute();
    }
    public function atualizarStatusPedido(
    int $pedidoId,
    string $status
    ): bool {
    $sql = "
        UPDATE pedidos
        SET status = :status
        WHERE id = :pedido_id
    ";

    $stmt = $this->pdo->prepare($sql);
    $stmt->bindValue(":status", $status, PDO::PARAM_STR);
    $stmt->bindValue(":pedido_id", $pedidoId, PDO::PARAM_INT);
    $stmt->execute();

    return $stmt->rowCount() === 1;
    }
    public function devolverEstoqueDoPedido(int $pedidoId): bool
    {
    $sql = "
        UPDATE estoque e
        SET quantidade = e.quantidade + i.quantidade,
            ultima_atualizacao = CURRENT_TIMESTAMP
        FROM itens_pedido i
        INNER JOIN pedidos p ON p.id = i.pedido_id
        WHERE p.id = :pedido_id
        AND e.produto_id = i.produto_id
        AND e.unidade_id = p.unidade_id
    ";

    $stmt = $this->pdo->prepare($sql);
    $stmt->bindValue(":pedido_id", $pedidoId, PDO::PARAM_INT);
    $stmt->execute();

    return $stmt->rowCount() > 0;
    }

    public function buscarPedidoPorId(int $pedidoId): array|false
    {
    $sql = "
        SELECT
            p.id,
            u.nome AS cliente,
            un.nome AS unidade,
            p.status,
            p.canal_pedido,
            p.forma_pagamento,
            p.valor_total
        FROM pedidos p
        INNER JOIN usuarios u ON u.id = p.cliente_id
        INNER JOIN unidades un ON un.id = p.unidade_id
        WHERE p.id = :pedido_id
    ";

    $stmt = $this->pdo->prepare($sql);
    $stmt->bindValue(":pedido_id", $pedidoId, PDO::PARAM_INT);
    $stmt->execute();

    return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function buscarPedidoParaAtualizacao(int $pedidoId): array|false
    {
    $sql = "
        SELECT id, status
        FROM pedidos
        WHERE id = :pedido_id
        FOR UPDATE
    ";

    $stmt = $this->pdo->prepare($sql);
    $stmt->bindValue(":pedido_id", $pedidoId, PDO::PARAM_INT);
    $stmt->execute();

    return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function listarEstoque(?int $unidadeId = null): array
    {
    $sql = "
        SELECT
            e.id,
            p.nome AS produto,
            u.nome AS unidade,
            e.quantidade,
            e.ultima_atualizacao
        FROM estoque e
        INNER JOIN produtos p ON p.id = e.produto_id
        INNER JOIN unidades u ON u.id = e.unidade_id
    ";

    if ($unidadeId !== null) {
        $sql .= " WHERE e.unidade_id = :unidade_id";
    }

    $sql .= " ORDER BY u.nome, p.nome";

    $stmt = $this->pdo->prepare($sql);

    if ($unidadeId !== null) {
        $stmt->bindValue(
            ":unidade_id",
            $unidadeId,
            PDO::PARAM_INT
        );
    }

    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function consultarFidelidade(int $usuarioId): array|false
    {
    $sql = "
        SELECT
            u.id AS usuario_id,
            u.nome,
            COALESCE(f.pontos, 0) AS pontos,
            f.data_ultima_atualizacao
        FROM usuarios u
        LEFT JOIN fidelidades f ON f.usuario_id = u.id
        WHERE u.id = :usuario_id
        AND u.perfil = :perfil
    ";

    $stmt = $this->pdo->prepare($sql);
    $stmt->bindValue(":usuario_id", $usuarioId, PDO::PARAM_INT);
    $stmt->bindValue(":perfil", "Cliente", PDO::PARAM_STR);
    $stmt->execute();

    return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function listarPagamentos(): array
    {
    $sql = "
        SELECT
            pg.id,
            pg.pedido_id,
            u.nome AS cliente,
            pg.status,
            pg.valor,
            pg.forma_pagamento,
            pg.data_pagamento
        FROM pagamentos pg
        INNER JOIN pedidos p ON p.id = pg.pedido_id
        INNER JOIN usuarios u ON u.id = p.cliente_id
        ORDER BY pg.id DESC
    ";

    $stmt = $this->pdo->query($sql);

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function listarAuditoria(): array
    {
    $sql = "
        SELECT
            a.id,
            u.nome AS usuario,
            a.acao,
            a.descricao,
            a.data_hora
        FROM auditoria a
        INNER JOIN usuarios u ON u.id = a.usuario_id
        ORDER BY a.id DESC
    ";

    $stmt = $this->pdo->query($sql);

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obterIndicadoresDashboard(): array
    {
    $sql = "
        SELECT
            COUNT(*) FILTER (
                WHERE created_at::date = CURRENT_DATE
            ) AS pedidos_dia,
            COUNT(*) FILTER (
                WHERE DATE_TRUNC('month', created_at)
                = DATE_TRUNC('month', CURRENT_DATE)
            ) AS pedidos_mes,
            COUNT(*) FILTER (
                WHERE status IN (
                    'AGUARDANDO_PAGAMENTO',
                    'EM_PREPARO',
                    'PRONTO'
                )
            ) AS pedidos_pendentes,
            COALESCE(
                (
                    SELECT SUM(valor)
                    FROM pagamentos
                    WHERE status = 'APROVADO'
                ),
                0
            ) AS faturamento
        FROM pedidos
    ";

    $stmt = $this->pdo->query($sql);

    return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}