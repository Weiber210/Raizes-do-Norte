-- Pedidos do sistema
 create table pedidos(
  id int auto_increment primary key,
  cliente_id int not null,
  unidade_id int not null,
  canal_pedido varchar(50) not null,
  status varchar(50) not null,
  forma_pagamento varchar(50) not null,
  valor_total decimal(10,2) not null,
  created_at timestamp default current_timestamp,
  foreign key (cliente_id) references usuarios(id),
  foreign key (unidade_id) references unidades(id)
 );