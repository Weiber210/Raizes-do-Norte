-- Adiciona o subtotal
alter table itens_pedido
add column subtotal decimal(10,2);

update itens_pedido
set subtotal = quantidade * valor_unitario
where subtotal is null;

alter table itens_pedido
alter column subtotal set not null;

-- Restringe canais 
alter table pedidos
add constraint chk_pedidos_canal
check (canal_pedido in ('APP', 'WEB', 'TOTEM', 'BALCAO', 'PICKUP'));

-- Um pagamento por pedido
alter table pagamentos
add constraint uq_pagamentos_pedido unique (pedido_id);

-- Evita estoque duplicado
alter table estoque
add constraint uq_estoque_produto_unidade unique (produto_id, unidade_id);

-- Evita quantidade negativa
alter table estoque
add constraint chk_estoque_quantidade
check (quantidade >= 0);