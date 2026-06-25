-- Estoque do Sistema
 create table estoque(
  id serial primary key,
  produto_id int not null,
  unidade_id int not null,
  quantidade int not null,
  ultima_atualizacao timestamp default current_timestamp,
  foreign key (produto_id) references produtos(id),
  foreign key (unidade_id) references unidades(id)
 );