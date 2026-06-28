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
}