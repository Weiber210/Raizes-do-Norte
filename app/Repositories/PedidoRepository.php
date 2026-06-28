<?php
class PedidoRepository{

    // Adicionar WHERE quando $canal estiver preenchido
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
    
}