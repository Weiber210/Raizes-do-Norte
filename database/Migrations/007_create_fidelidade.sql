-- Fidelidades do Sistema
 create table fidelidades(
  id int auto_increment primary key,
  usuario_id int not null,
  pontos int not null,
  data_ultima_atualizacao timestamp default current_timestamp,
  foreign key (usuario_id) references usuarios(id)
 );