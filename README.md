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

## Banco de dados

Execute todas as migrations na ordem numérica, de `001` até `011`.

Depois execute os seeds na ordem numérica, de `001` até `005`.

## Usuários de demonstração

Todos utilizam a senha `Teste@123`.

- `admin@teste.com` — Administrador
- `gerente@teste.com` — Gerente
- `atendente@teste.com` — Atendente
- `cozinha@teste.com` — Cozinha
- `cliente@teste.com` — Cliente

Essas contas devem ser utilizadas somente para demonstração e testes.

## Postman

1. Importe os dois arquivos disponíveis em `tests/Postman`.
2. Selecione o ambiente `Raízes Local`.
3. Preencha `clienteId` com o ID do Cliente de teste.
4. Execute a coleção pelo Collection Runner.

A execução validada possui 24 testes aprovados e nenhuma falha.

## Evidências

As evidências do Postman estão disponíveis em `docs/evidencias`.

## Endpoints principais

- `POST /api/auth/login`
- `GET /api/produtos`
- `GET /api/pedidos`
- `POST /api/pedidos`
- `GET /api/pedidos/{id}`
- `DELETE /api/pedidos/{id}`
- `POST /api/pedidos/{id}/pagamentos`
- `PATCH /api/pedidos/{id}/status`
- `GET /api/estoque`
- `GET /api/fidelidade/{usuarioId}`
- `GET /api/pagamentos`
- `GET /api/auditoria`