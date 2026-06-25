 -- Usuários do Sistema
 create table usuarios(
 id int auto_increment primary key
 nome varchar(100) not null
 email varchar(150) not null unique
 senha varchar(255) not null
 perfil varchar(50) not null
 consentimento_lgpd boolean not null
 created_at timestamp default current_timestamp
 );
