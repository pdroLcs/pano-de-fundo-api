# Pano de Fundo API

> API Laravel para gerenciar produtos, categorias, compras e mensagens (fale-conosco).

## Visão geral

Projeto backend em Laravel que expõe uma API RESTful (prefixo `/api/v1`) para gerenciar usuários (clientes), categorias, produtos, compras e mensagens de contato. Autenticação via Laravel Sanctum com capacidades/abilities para papéis (admin / cliente).

## Requisitos

- PHP 8.x
- Composer
- MySQL / MariaDB (ou outro banco suportado pelo Laravel)
- Node.js + npm (opcional, para assets)
- Laragon (opcional para ambiente local)

## Instalação

1. Clone o repositório:

   git clone <repo-url> pano-de-fundo-api
   cd pano-de-fundo-api

2. Instale dependências PHP:

   composer install

3. Copie e edite o arquivo de ambiente:

   cp .env.example .env

   - Configure a conexão com o banco: `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD`.

4. Gere a chave da aplicação:

   php artisan key:generate

5. Rode migrações:

   php artisan migrate

6. Crie o link de storage:

   php artisan storage:link

7. Inicie o servidor local (ou use Laragon):

   php artisan serve --host=127.0.0.1 --port=8000

O endpoint base da API será `http://127.0.0.1:8000/api/v1`.

## Autenticação

Esta API utiliza Laravel Sanctum para autenticação via tokens.

Exemplo de registro e login:

```bash
curl -X POST http://127.0.0.1:8000/api/v1/register \
  -H "Content-Type: application/json" \
  -d '{"name":"João","email":"joao@examplo.com","password":"secreta"}'

curl -X POST http://127.0.0.1:8000/api/v1/login \
  -H "Content-Type: application/json" \
  -d '{"email":"joao@examplo.com","password":"secreta"}'
```

O registro e login retorna um token que deve ser usado no header `Authorization: Bearer <token>` para rotas protegidas.

## Endpoints principais

Base: `/api/v1`

Públicos:
- `POST /register` — registrar usuário
- `POST /login` — autenticar e obter token
- `GET /produtos` — listar produtos
- `GET /produtos/{produto}` — ver detalhe do produto

Autenticados (`auth:sanctum`):
- `POST /logout` — encerrar sessão
- `GET /compras` — listar compras (user)
- `GET /compras/{id}` — detalhes da compra
- `GET|DELETE|SHOW` em `clientes` conforme permissões
- `POST|PUT|DELETE` em `fale-conosco` (CRUD de mensagens)

Rotas para `admin` (middleware `abilities:admin`):
- `GET /clientes` — listar clientes
- CRUD completo para `/categorias` e `/produtos`
- `DELETE /compras/{compra}` — remover compra

Rotas para `cliente` (middleware `ability:cliente`):
- `POST /comprar/{produto}` — finalizar compra do produto
- `PUT /clientes/{id}` — atualizar próprio cliente

Observação: Consulte `routes/api.php` para detalhes de rotas e permissões.

## Estrutura do projeto (resumo)

- `app/Models` — modelos Eloquent (User, Produto, Categoria, Compra, Mensagem, ItensCompra)
- `app/Http/Controllers/Api` — controladores da API
- `routes/api.php` — definição das rotas da API
- `database/migrations` — migrações do banco