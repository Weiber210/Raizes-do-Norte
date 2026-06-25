-- Produtos do sistema
create table produtos(
  id serial primary key,
  nome varchar(100) not null,
  descricao varchar(255) not null,
  preco decimal(10,2) not null,
  ativo boolean not null
 );