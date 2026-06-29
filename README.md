# Raízes do Nordeste

Sistema back-end para gestão de pedidos, produtos, estoque, pagamentos e fidelidade de uma rede de lanchonetes.

## Tecnologias

- PHP 8
- PostgreSQL
- PDO
- JWT
- HTML
- Bootstrap
- Swagger/OpenAPI
- Postman

## Arquitetura

Frontend → Controllers → Services → Repositories → PostgreSQL

## Configuração

1. Copie `.env.example` para `.env`.
2. Configure as credenciais do PostgreSQL.
3. Configure `JWT_SECRET` com pelo menos 32 caracteres.
4. Execute as migrations em ordem.
5. Execute os seeds em ordem.

## Acesso

Painel:

`http://localhost/Raizes-do-Norte/public/`

Swagger:

`http://localhost/Raizes-do-Norte/public/swagger/`

API:

`http://localhost/Raizes-do-Norte/public/api`

## Fluxo do pedido

1. Cadastro do pedido.
2. Validação e baixa do estoque.
3. Pagamento mock.
4. Preparação.
5. Pedido pronto.
6. Entrega.

## Perfis

- Administrador
- Gerente
- Atendente
- Cozinha
- Cliente

## Segurança

- Senhas armazenadas com hash.
- Autenticação JWT na API.
- Controle de acesso por perfil.
- Consentimento LGPD.
- Auditoria.
- Logs internos.
- Transações para pedidos e pagamentos.

## Testes

Importe a coleção disponível em `tests/Postman`.

A coleção contém cenários positivos e negativos da API.