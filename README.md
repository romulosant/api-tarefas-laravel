# API de Tarefas

API REST para gerenciamento de tarefas.

Com esta API é possível criar, listar, visualizar, atualizar, concluir e deletar tarefas. A API também permite filtrar tarefas por status, buscar por título, filtrar por data de criação e navegar pelos resultados com paginação.

---

## Sumário

- [Requisitos](#requisitos)
- [Como instalar](#como-instalar)
- [Como configurar o ambiente](#como-configurar-o-ambiente)
- [Como executar o projeto](#como-executar-o-projeto)
- [URL base da API](#url-base-da-api)
- [Status disponíveis](#status-disponíveis)
- [Endpoints](#endpoints)
  - [Listar tarefas](#listar-tarefas)
  - [Criar tarefa](#criar-tarefa)
  - [Visualizar tarefa](#visualizar-tarefa)
  - [Atualizar tarefa](#atualizar-tarefa)
  - [Concluir tarefa](#concluir-tarefa)
  - [Deletar tarefa](#deletar-tarefa)
- [Filtros](#filtros)
- [Paginação](#paginação)
- [Retornos da API](#retornos-da-api)
- [Erros de validação](#erros-de-validação)
- [Como testar no Postman ou Insomnia](#como-testar-no-postman-ou-insomnia)
- [Exemplos rápidos](#exemplos-rápidos)
- [Observações](#observações)

---

## Requisitos

Antes de executar o projeto, é necessário ter instalado:

- PHP
- Composer
- Laravel
- MySQL, SQLite ou outro banco compatível
- Git

---

## Como instalar

Clone o repositório:

```bash
git clone https://github.com/SEU-USUARIO/api-tarefas-laravel.git
```

Acesse a pasta do projeto:

```bash
cd api-tarefas-laravel
```

Instale as dependências:

```bash
composer install
```

Crie o arquivo de ambiente:

```bash
cp .env.example .env
```

Gere a chave da aplicação:

```bash
php artisan key:generate
```

---

## Como configurar o ambiente

Abra o arquivo `.env` e configure o banco de dados.

Exemplo usando MySQL:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=api_tarefas
DB_USERNAME=root
DB_PASSWORD=
```

Depois execute as migrations e os seeders:

```bash
php artisan migrate --seed
```

Esse comando cria as tabelas do banco e também preenche os dados iniciais necessários para a API funcionar corretamente, como os status das tarefas.

Se você já executou as migrations antes e precisa apenas preencher os dados iniciais, rode:

```bash
php artisan db:seed
```

Em ambiente de desenvolvimento, caso queira apagar e recriar todas as tabelas do zero com os dados iniciais, use:

```bash
php artisan migrate:fresh --seed
```

> Atenção: o comando `migrate:fresh --seed` apaga todas as tabelas do banco antes de recriá-las.

---

## Como executar o projeto

Inicie o servidor local:

```bash
php artisan serve
```

Por padrão, a aplicação ficará disponível em:

```text
http://127.0.0.1:8000
```

---

## URL base da API

Todas as rotas da API começam com:

```text
http://127.0.0.1:8000/api
```

Exemplo:

```text
http://127.0.0.1:8000/api/tasks
```

---

## Status disponíveis

As tarefas usam uma tabela auxiliar de status.

| ID | Status |
|---|---|
| 1 | pending |
| 2 | in_progress |
| 3 | done |

Ao criar uma tarefa, o campo `status_id` pode ser informado.

Se nenhum status for enviado, a API considera a tarefa como pendente.

---

# Endpoints

## Listar tarefas

Retorna uma lista paginada de tarefas.

### Requisição

```http
GET /api/tasks
```

### Exemplo

```http
GET http://127.0.0.1:8000/api/tasks
```

### Resposta de sucesso

```json
{
  "message": "Tarefas listadas com sucesso",
  "data": [
    {
      "id": 1,
      "title": "Estudar Laravel",
      "description": "Praticar criação de APIs",
      "status": {
        "id": 1,
        "nome": "Pendente"
      },
      "created_at": "2026-07-04 20:00:00",
      "updated_at": "2026-07-04 20:00:00"
    }
  ],
  "pagination": {
    "current_page": 1,
    "per_page": 15,
    "total": 1,
    "last_page": 1
  }
}
```

---

## Criar tarefa

Cria uma nova tarefa.

### Requisição

```http
POST /api/tasks
```

### Body

```json
{
  "title": "Estudar Laravel",
  "description": "Praticar testes de API",
  "status_id": 1
}
```

### Campos

| Campo | Obrigatório | Descrição |
|---|---|---|
| title | Sim | Título da tarefa |
| description | Não | Descrição da tarefa |
| status_id | Não | ID do status da tarefa |

### Exemplo

```http
POST http://127.0.0.1:8000/api/tasks
```

### Resposta de sucesso

Status HTTP:

```http
201 Created
```

Resposta:

```json
{
  "message": "Tarefa criada com sucesso",
  "data": {
    "id": 1,
    "title": "Estudar Laravel",
    "description": "Praticar testes de API",
    "status": {
      "id": 1,
      "nome": "Pendente"
    },
    "created_at": "2026-07-04 20:00:00",
    "updated_at": "2026-07-04 20:00:00"
  }
}
```

---

## Visualizar tarefa

Retorna os dados de uma tarefa específica.

### Requisição

```http
GET /api/tasks/{task}
```

### Exemplo

```http
GET http://127.0.0.1:8000/api/tasks/1
```

### Resposta de sucesso

```json
{
  "message": "Tarefa encontrada com sucesso",
  "data": {
    "id": 1,
    "title": "Estudar Laravel",
    "description": "Praticar testes de API",
    "status": {
      "id": 1,
      "nome": "Pendente"
    },
    "created_at": "2026-07-04 20:00:00",
    "updated_at": "2026-07-04 20:00:00"
  }
}
```

---

## Atualizar tarefa

Atualiza uma tarefa existente.

### Requisição

```http
PATCH /api/tasks/{task}
```

### Exemplo

```http
PATCH http://127.0.0.1:8000/api/tasks/1
```

### Body

```json
{
  "title": "Estudar Laravel API",
  "description": "Praticar endpoints REST",
  "status_id": 2
}
```

### Campos que podem ser atualizados

| Campo | Descrição |
|---|---|
| title | Título da tarefa |
| description | Descrição da tarefa |
| status_id | Status da tarefa |

### Resposta de sucesso

```json
{
  "message": "Tarefa atualizada com sucesso",
  "data": {
    "id": 1,
    "title": "Estudar Laravel API",
    "description": "Praticar endpoints REST",
    "status": {
      "id": 2,
      "nome": "Em andamento"
    },
    "created_at": "2026-07-04 20:00:00",
    "updated_at": "2026-07-04 20:30:00"
  }
}
```

---

## Concluir tarefa

Marca uma tarefa como concluída.

Ao usar este endpoint, a API altera o status da tarefa para `Concluída`.

### Requisição

```http
PATCH /api/tasks/{task}/complete
```

### Exemplo

```http
PATCH http://127.0.0.1:8000/api/tasks/1/complete
```

### Resposta de sucesso

```json
{
  "message": "Tarefa concluída com sucesso",
  "data": {
    "id": 1,
    "title": "Estudar Laravel API",
    "description": "Praticar endpoints REST",
    "status": {
      "id": 3,
      "nome": "Concluída"
    },
    "created_at": "2026-07-04 20:00:00",
    "updated_at": "2026-07-04 21:00:00"
  }
}
```

---

## Deletar tarefa

Remove uma tarefa.

### Requisição

```http
DELETE /api/tasks/{task}
```

### Exemplo

```http
DELETE http://127.0.0.1:8000/api/tasks/1
```

### Resposta de sucesso

Status HTTP:

```http
204 No Content
```

Este retorno não possui corpo de resposta.

---

# Filtros

A listagem de tarefas aceita filtros via query params.

## Filtrar por status

### Requisição

```http
GET /api/tasks?status_id=1
```

### Exemplos

Listar tarefas pendentes:

```http
GET http://127.0.0.1:8000/api/tasks?status_id=1
```

Listar tarefas em andamento:

```http
GET http://127.0.0.1:8000/api/tasks?status_id=2
```

Listar tarefas concluídas:

```http
GET http://127.0.0.1:8000/api/tasks?status_id=3
```

---

## Buscar por título

Busca tarefas que contenham o texto informado no título.

### Requisição

```http
GET /api/tasks?title=Laravel
```

### Exemplo

```http
GET http://127.0.0.1:8000/api/tasks?title=Laravel
```

---

## Filtrar por data de criação

Filtra tarefas pela data de criação.

### Requisição

```http
GET /api/tasks?date=2026-07-04
```

### Exemplo

```http
GET http://127.0.0.1:8000/api/tasks?date=2026-07-04
```

---

## Usar mais de um filtro

Os filtros podem ser combinados.

### Exemplo

```http
GET http://127.0.0.1:8000/api/tasks?status_id=1&title=Laravel
```

Esse exemplo retorna tarefas pendentes que possuem `Laravel` no título.

---

# Paginação

A listagem de tarefas é paginada.

### Requisição

```http
GET /api/tasks?page=1
```

### Exemplo

```http
GET http://127.0.0.1:8000/api/tasks?page=1
```

### Retorno da paginação

```json
{
  "pagination": {
    "current_page": 1,
    "per_page": 15,
    "total": 45,
    "last_page": 3
  }
}
```

### Campos da paginação

| Campo | Descrição |
|---|---|
| current_page | Página atual |
| per_page | Quantidade de registros por página |
| total | Total de registros encontrados |
| last_page | Última página disponível |

---

# Retornos da API

## Sucesso ao listar

```http
200 OK
```

```json
{
  "message": "Tarefas listadas com sucesso",
  "data": [],
  "pagination": {
    "current_page": 1,
    "per_page": 15,
    "total": 0,
    "last_page": 1
  }
}
```

---

## Sucesso ao criar

```http
201 Created
```

```json
{
  "message": "Tarefa criada com sucesso",
  "data": {
    "id": 1,
    "title": "Estudar Laravel",
    "description": "Praticar testes de API",
    "status": {
      "id": 1,
      "nome": "Pendente"
    }
  }
}
```

---

## Sucesso ao atualizar

```http
200 OK
```

```json
{
  "message": "Tarefa atualizada com sucesso",
  "data": {
    "id": 1,
    "title": "Título atualizado",
    "description": "Descrição atualizada",
    "status": {
      "id": 2,
      "nome": "Em andamento"
    }
  }
}
```

---

## Sucesso ao concluir

```http
200 OK
```

```json
{
  "message": "Tarefa concluída com sucesso",
  "data": {
    "id": 1,
    "title": "Título da tarefa",
    "description": "Descrição da tarefa",
    "status": {
      "id": 3,
      "nome": "Concluída"
    }
  }
}
```

---

## Sucesso ao deletar

```http
204 No Content
```

Sem corpo de resposta.

---

# Erros de validação

Quando algum campo obrigatório não é enviado ou algum valor inválido é informado, a API retorna erro de validação.

## Exemplo: criar tarefa sem título

### Requisição

```http
POST /api/tasks
```

### Body

```json
{
  "description": "Tarefa sem título",
  "status_id": 1
}
```

### Resposta

```http
422 Unprocessable Entity
```

```json
{
  "message": "The title field is required.",
  "errors": {
    "title": [
      "The title field is required."
    ]
  }
}
```

---

## Exemplo: status inválido

### Body

```json
{
  "title": "Estudar Laravel",
  "description": "Praticar API",
  "status_id": 99
}
```

### Resposta esperada

```http
422 Unprocessable Entity
```

```json
{
  "message": "The selected status id is invalid.",
  "errors": {
    "status_id": [
      "The selected status id is invalid."
    ]
  }
}
```

---

# Como testar no Postman ou Insomnia

## 1. Inicie o servidor

```bash
php artisan serve
```

## 2. Crie uma nova requisição

Use a URL base:

```text
http://127.0.0.1:8000/api
```

## 3. Configure o método HTTP

Exemplos:

```text
GET
POST
PATCH
DELETE
```

## 4. Configure os headers

Para requisições com body JSON, use:

```http
Content-Type: application/json
Accept: application/json
```

## 5. Envie o body em JSON

Exemplo para criar tarefa:

```json
{
  "title": "Estudar Laravel",
  "description": "Praticar API REST",
  "status_id": 1
}
```

---

# Exemplos rápidos

## Criar tarefa

```http
POST /api/tasks
```

```json
{
  "title": "Comprar material",
  "description": "Comprar caderno e caneta",
  "status_id": 1
}
```

---

## Listar tarefas

```http
GET /api/tasks
```

---

## Buscar por título

```http
GET /api/tasks?title=material
```

---

## Filtrar concluídas

```http
GET /api/tasks?status_id=3
```

---

## Concluir tarefa

```http
PATCH /api/tasks/1/complete
```

---

## Deletar tarefa

```http
DELETE /api/tasks/1
```

---

# Observações

- A API retorna dados em formato JSON.
- O campo `title` é obrigatório.
- O campo `description` é opcional.
- O campo `status_id` representa o status da tarefa.
- Se a tarefa for concluída, o status passa a ser `Concluída`.
- A exclusão retorna `204 No Content`.
- A listagem retorna dados paginados.