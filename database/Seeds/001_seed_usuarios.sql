-- Usuários Demonstração
insert into usuarios (nome, email, senha, perfil, consentimento_lgpd, ativo) values
(
    'Administrador de teste',
    'admin@teste.com',
    '$2y$10$XjwKA36LszUDRjkvpcvDwO6PJEZakQnSNdF1x0gbTIZY.c.8rXsVC',
    'Administrador',
    true,
    true
),
(
    'Gerente de teste',
    'gerente@teste.com',
    '$2y$10$XjwKA36LszUDRjkvpcvDwO6PJEZakQnSNdF1x0gbTIZY.c.8rXsVC',
    'Gerente',
    true,
    true
),
(
    'Atendente de teste',
    'atendente@teste.com',
    '$2y$10$XjwKA36LszUDRjkvpcvDwO6PJEZakQnSNdF1x0gbTIZY.c.8rXsVC',
    'Atendente',
    true,
    true
),
(
    'Cozinha de teste',
    'cozinha@teste.com',
    '$2y$10$XjwKA36LszUDRjkvpcvDwO6PJEZakQnSNdF1x0gbTIZY.c.8rXsVC',
    'Cozinha',
    true,
    true
),
(
    'Cliente de teste',
    'cliente@teste.com',
    '$2y$10$XjwKA36LszUDRjkvpcvDwO6PJEZakQnSNdF1x0gbTIZY.c.8rXsVC',
    'Cliente',
    true,
    true
)
on conflict (email) do nothing;