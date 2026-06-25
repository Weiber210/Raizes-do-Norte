-- Unidades do sistema
 create table unidades(
  id serial primary key,
  nome varchar(100) not null,
  cidade varchar(255) not null,
  estado varchar(2) not null,
  ativa boolean not null
 );