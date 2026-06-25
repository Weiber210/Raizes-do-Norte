-- Itens Pedidos do Sistema
 create table itens_pedido(
  id serial primary key,
  pedido_id int not null,
  produto_id int not null,
  quantidade int not null,
  valor_unitario decimal(10,2) not null,
  foreign key (pedido_id) references pedidos(id),
  foreign key (produto_id) references produtos(id)
 );