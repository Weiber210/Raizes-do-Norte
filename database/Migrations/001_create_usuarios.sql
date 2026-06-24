 -- Usuários do Sistema
 id int auto_increment primary key
 nome varchar(100) not null
 email varchar(150) not null unique
 senha varchar(255) no null
 perfil varchar(50) no null
 consentimento_lgpd boolean not null
 created_at timestamp default current_timestamp
 
