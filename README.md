# 🎫 HelpDeskFlow
> Sistema completo de Help Desk desenvolvido com Laravel 12

[![Laravel](https://img.shields.io/badge/Laravel-12-red?style=for-the-badge&logo=laravel)](https://laravel.com)
[![PHP](https://img.shields.io/badge/PHP-8.2-blue?style=for-the-badge&logo=php)](https://php.net)
[![Bootstrap](https://img.shields.io/badge/Bootstrap-5-purple?style=for-the-badge&logo=bootstrap)](https://getbootstrap.com)
[![License](https://img.shields.io/badge/License-MIT-green?style=for-the-badge)](LICENSE)

---

## 📖 Sobre o Projeto

HelpDeskFlow é um sistema de atendimento desenvolvido **com padrões profissionais** para demonstrar exatamente o que empresas buscam em um desenvolvedor júnior. Não é só um CRUD - é um projeto completo com arquitetura em camadas, controle de acesso, validação rigorosa e fluxo de trabalho real.

💡 **Por que esse projeto se destaca no portfólio:**
> Esse sistema não é apenas um exercício. Ele simula um ambiente real de trabalho, com as mesmas regras e estrutura que você encontraria em empresas.

✅ **O que esse projeto demonstra:**
- ✅ Organização de código e separação de responsabilidades
- ✅ Autenticação e autorização granular
- ✅ Relacionamentos entre tabelas
- ✅ Validação de dados no servidor
- ✅ Tratamento de regras de negócio
- ✅ API REST padrão
- ✅ Boas práticas de versionamento

---

## ✨ Funcionalidades

### 🔐 Autenticação & Autorização
- Sistema de login/logout seguro
- **3 perfis de acesso**: Administrator, Atendente, Solicitante
- Policies para controle granular de permissões
- Proteção CSRF e Rate Limiting

### 🎫 Gestão de Chamados
- ✅ Criar, visualizar, editar e excluir tickets
- 📊 Status: Aberto, Em Andamento, Aguardando, Resolvido, Fechado
- ⚡ Prioridades: Baixa, Média, Alta, Urgente
- 🔍 Filtros avançados por status, prioridade e busca
- 📄 Paginação eficiente
- 💬 Sistema de comentários
- 📜 Histórico COMPLETO de alterações

### 📊 Dashboard
- Cards com métricas em tempo real
- Distribuição por status
- Distribuição por prioridade
- Chamados resolvidos no mês

### ⚡ Arquitetura Profissional
- **Service Layer** - toda regra de negócio centralizada
- **Policy-based Authorization** - controle de acesso por perfil
- **Eloquent Relationships** - relacionamentos bem definidos
- **Validação Server-side** - nenhum dado entra sem validação

### 🔌 API REST
- Endpoints para todos os recursos
- Autenticação via token
- Relatórios e sumários

---

## 🚀 Instalação

### Requisitos
- PHP 8.2+
- Composer 2.x
- SQLite / MySQL / PostgreSQL

### Passo a passo

```bash
# 1. Clone o repositório
git clone https://github.com/seu-usuario/helpdeskflow.git
cd helpdeskflow

# 2. Instale as dependências
composer install

# 3. Configure o ambiente
cp .env.example .env
php artisan key:generate

# 4. Crie o banco e as tabelas
php artisan migrate:fresh

# 5. Popule com dados de exemplo
php artisan db:seed

# 6. Inicie o servidor
php artisan serve
```

---

## 🔑 Usuários para Teste

| Perfil | Email | Senha |
|---|---|---|
| 👑 Administrador | `admin@example.com` | `password` |
| 👨‍💼 Atendente | `atendente@example.com` | `password` |
| 👤 Solicitante | `solicitante@example.com` | `password` |

---

## 🛠️ Tecnologias Utilizadas

| Camada | Tecnologia |
|---|---|
| Backend | PHP 8.2 + Laravel 12 |
| Frontend | Bootstrap 5 |
| Banco de Dados | SQLite / MySQL |
| Autenticação | Laravel Auth nativo |
| Autorização | Policies e Gates |

---

---

# 🌐 English Version

---

## 📖 About the Project

HelpDeskFlow is a complete helpdesk system built **with professional standards** to demonstrate exactly what companies look for in a junior developer. It's not just another CRUD - it's a full project with layered architecture, access control, strict validation and real workflow.

💡 **Why this project stands out in your portfolio:**
> This system is not just an exercise. It simulates a real work environment, with the same rules and structure you would find in companies.

✅ **What this project demonstrates:**
- ✅ Code organization and separation of concerns
- ✅ Authentication and granular authorization
- ✅ Database relationships
- ✅ Server-side data validation
- ✅ Business rules implementation
- ✅ Standard REST API
- ✅ Good versioning practices

---

## ✨ Features

### 🔐 Authentication & Authorization
- Secure login/logout system
- **3 access roles**: Administrator, Agent, Requester
- Policies for granular permission control
- CSRF Protection and Rate Limiting

### 🎫 Ticket Management
- ✅ Create, view, edit and delete tickets
- 📊 Status: Open, In Progress, Waiting, Resolved, Closed
- ⚡ Priorities: Low, Medium, High, Urgent
- 🔍 Advanced filters by status, priority and search
- 📄 Efficient pagination
- 💬 Comment system
- 📜 FULL change history

### 📊 Dashboard
- Real-time metric cards
- Status distribution
- Priority distribution
- Monthly resolved tickets

### ⚡ Professional Architecture
- **Service Layer** - all business logic centralized
- **Policy-based Authorization** - role-based access control
- **Eloquent Relationships** - well defined database relations
- **Server-side Validation** - no data enters without validation

### 🔌 REST API
- Endpoints for all resources
- Token authentication
- Reports and summaries

---

## 🚀 Installation

### Requirements
- PHP 8.2+
- Composer 2.x
- SQLite / MySQL / PostgreSQL

### Step by step

```bash
# 1. Clone the repository
git clone https://github.com/your-username/helpdeskflow.git
cd helpdeskflow

# 2. Install dependencies
composer install

# 3. Configure environment
cp .env.example .env
php artisan key:generate

# 4. Create database tables
php artisan migrate:fresh

# 5. Seed test data
php artisan db:seed

# 6. Start development server
php artisan serve
```

---

## 🔑 Test Users

| Role | Email | Password |
|---|---|---|
| 👑 Administrator | `admin@example.com` | `password` |
| 👨‍💼 Agent | `agent@example.com` | `password` |
| 👤 Requester | `requester@example.com` | `password` |

---

## 🛠️ Tech Stack

| Layer | Technology |
|---|---|
| Backend | PHP 8.2 + Laravel 12 |
| Frontend | Bootstrap 5 |
| Database | SQLite / MySQL |
| Authentication | Native Laravel Auth |
| Authorization | Policies and Gates |