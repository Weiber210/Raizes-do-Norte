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

## Pré-requisitos

- Apache 2.4 com `mod_rewrite` habilitado.
- PHP 8.2 ou superior.
- Extensões PHP `PDO`, `pdo_pgsql`, `pgsql`, `mbstring`, `openssl` e `json`.
- PostgreSQL 15 ou superior, local ou hospedado.
- Cliente `psql` para executar os arquivos do banco de dados.

## Arquitetura

Frontend → Controllers → Services → Repositories → PostgreSQL

## Configuração

1. Copie `.env.example` para `.env`.
2. Configure as credenciais do PostgreSQL.
3. Configure `JWT_SECRET` com pelo menos 32 caracteres.
4. Execute as migrations e os seeds conforme a seção Banco de dados.
5. Inicie o Apache e acesse o sistema.

## Acesso

Painel:

`http://localhost/Raizes-do-Norte/public/`

Swagger:

`http://localhost/Raizes-do-Norte/public/swagger/`

API:

`http://localhost/Raizes-do-Norte/public/api`

Versão hospedada:

`https://lightcoral-hare-673185.hostingersite.com/`

No Swagger, o servidor `Ambiente atual` funciona tanto no XAMPP quanto na hospedagem. A opção `Hostinger` aponta diretamente para a API publicada.

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

Execute as migrations na ordem abaixo, substituindo os dados de conexão pelos dados do seu PostgreSQL:

```bash
psql -h SEU_HOST -p 5432 -U SEU_USUARIO -d SEU_BANCO -f database/Migrations/001_create_usuarios.sql
psql -h SEU_HOST -p 5432 -U SEU_USUARIO -d SEU_BANCO -f database/Migrations/002_Create_unidade.sql
psql -h SEU_HOST -p 5432 -U SEU_USUARIO -d SEU_BANCO -f database/Migrations/003_create_produtos.sql
psql -h SEU_HOST -p 5432 -U SEU_USUARIO -d SEU_BANCO -f database/Migrations/004_create_pedidos.sql
psql -h SEU_HOST -p 5432 -U SEU_USUARIO -d SEU_BANCO -f database/Migrations/005_create_itens_pedido.sql
psql -h SEU_HOST -p 5432 -U SEU_USUARIO -d SEU_BANCO -f database/Migrations/006_create_pagamentos.sql
psql -h SEU_HOST -p 5432 -U SEU_USUARIO -d SEU_BANCO -f database/Migrations/007_create_fidelidade.sql
psql -h SEU_HOST -p 5432 -U SEU_USUARIO -d SEU_BANCO -f database/Migrations/008_create_auditoria.sql
psql -h SEU_HOST -p 5432 -U SEU_USUARIO -d SEU_BANCO -f database/Migrations/009_create_estoque.sql
psql -h SEU_HOST -p 5432 -U SEU_USUARIO -d SEU_BANCO -f database/Migrations/010_ajustes_integridade.sql
psql -h SEU_HOST -p 5432 -U SEU_USUARIO -d SEU_BANCO -f database/Migrations/011_reforco_integridade.sql
```

Depois execute os seeds:

```bash
psql -h SEU_HOST -p 5432 -U SEU_USUARIO -d SEU_BANCO -f database/Seeds/001_seed_usuarios.sql
psql -h SEU_HOST -p 5432 -U SEU_USUARIO -d SEU_BANCO -f database/Seeds/002_seed_unidades.sql
psql -h SEU_HOST -p 5432 -U SEU_USUARIO -d SEU_BANCO -f database/Seeds/003_seed_produtos.sql
psql -h SEU_HOST -p 5432 -U SEU_USUARIO -d SEU_BANCO -f database/Seeds/004_seed_estoque.sql
psql -h SEU_HOST -p 5432 -U SEU_USUARIO -d SEU_BANCO -f database/Seeds/005_seed_fidelidade.sql
```

No Supabase, também é possível abrir o SQL Editor e executar o conteúdo desses arquivos, um por vez, na mesma ordem.

## mod_rewrite

O arquivo `public/.htaccess` direciona as URLs `/api` para o arquivo de entrada da API. Para funcionar, o Apache precisa estar com o `mod_rewrite` habilitado e a opção `AllowOverride All` configurada para a pasta do projeto.

No XAMPP, confirme que a linha abaixo está habilitada no arquivo `apache/conf/httpd.conf`:

```apache
LoadModule rewrite_module modules/mod_rewrite.so
```

Depois, reinicie o Apache. Na Hostinger, o `mod_rewrite` normalmente já está habilitado.

## Implantação na Hostinger

1. Se for possível alterar a raiz do domínio, mantenha a estrutura do projeto e defina a pasta `public` como raiz pública.
2. Se a raiz do domínio for obrigatoriamente `public_html`, envie o conteúdo da pasta `public` para `public_html` e coloque `app`, `config`, `database`, `docs`, `routes`, `storage` e `.env` um nível acima de `public_html`.
3. Selecione PHP 8.2 ou superior e habilite `pdo_pgsql` e `pgsql`.
4. Preencha o `.env` com os dados do banco de produção e uma chave `JWT_SECRET` própria.
5. Execute as migrations e os seeds uma única vez no banco de produção.
6. Garanta permissão de escrita na pasta `storage/logs`.
7. Acesse a página inicial, faça login e teste `/api/auth/login` e `/swagger/`.

O arquivo `.env` deve permanecer fora da pasta pública e não deve ser enviado ao GitHub.

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
