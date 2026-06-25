-- Auditoria do Sistema
 create table auditoria(
  id int auto_increment primary key,
  usuario_id int not null,
  acao varchar(100) not null,
  descricao varchar(255) not null,
  data_hora timestamp default current_timestamp,
  foreign key (usuario_id) references usuarios(id)
 );