<?php
class PedidoService{

    private const CANAIS = [
        "APP",
        "WEB",
        "TOTEM",
        "BALCAO",
        "PICKUP"
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

}