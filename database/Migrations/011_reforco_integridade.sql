-- Desativar usuários sem apagar seu histórico
alter table usuarios
add column ativo boolean not null default true;

-- Status para pedidos
alter table pedidos
add constraint chk_pedidos_status
check (
    status in (
        'AGUARDANDO_PAGAMENTO',
        'EM_PREPARO',
        'PRONTO',
        'ENTREGUE',
        'CANCELADO'
    )
);

-- Formas de pagamento
alter table pedidos
add constraint chk_pedidos_forma_pagamento
check (
    forma_pagamento in (
        'DEBITO',
        'CREDITO',
        'PIX'
    )
);

-- Resultados pagamentos
alter table pagamentos
add constraint chk_pagamentos_status
check (
    status in (
        'APROVADO',
        'RECUSADO'
    )
);

alter table produtos
add constraint chk_produtos_preco
check (preco >= 0);

alter table itens_pedido
add constraint chk_itens_quantidade
check (quantidade > 0);

alter table itens_pedido
add constraint chk_itens_valores
check (
    valor_unitario >= 0
    and subtotal >= 0
);

alter table fidelidades
add constraint chk_fidelidades_pontos
check (pontos >= 0);

create unique index if not exists
uq_fidelidades_usuario
on fidelidades(usuario_id);

create index if not exists
idx_pedidos_cliente
on pedidos(cliente_id);

create index if not exists
idx_pedidos_unidade
on pedidos(unidade_id);

create index if not exists
idx_pedidos_canal
on pedidos(canal_pedido);

create index if not exists
idx_itens_pedido_pedido
on itens_pedido(pedido_id);

create index if not exists
idx_pagamentos_pedido
on pagamentos(pedido_id);

update unidades
set nome = 'Matriz Raízes do Nordeste'
where nome = 'Matriz Raízes do Norte';