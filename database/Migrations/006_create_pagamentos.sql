-- Pagamentos do Sistema
 create table pagamentos(
  id int auto_increment primary key,
  pedido_id int not null,
  status varchar(50) not null,
  valor decimal(10,2) not null,
  forma_pagamento varchar(50) not null,
  data_pagamento timestamp default current_timestamp,
  foreign key (pedido_id) references pedidos(id)
 );