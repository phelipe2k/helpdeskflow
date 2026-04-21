# HelpDeskFlow

HelpDeskFlow is a helpdesk system built with Laravel that allows companies to manage user registration, ticket creation, status tracking, assignments, comments, and basic reports.

## Features

- User authentication with roles (Administrator, Atendente, Solicitante)
- Ticket management with priorities, statuses, categories
- Comments and history tracking
- Filters and pagination
- Basic dashboard with metrics
- REST API
- Multi-language support (pt-BR, en)

## Technologies

- PHP 8.x
- Laravel 12
- MySQL/PostgreSQL
- Bootstrap 5

## Installation

1. Clone the repository
2. Run `composer install`
3. Copy `.env.example` to `.env` and configure database
4. Run `php artisan key:generate`
5. Run `php artisan migrate`
6. Run `php artisan db:seed`
7. Run `php artisan serve`

## Usage

- Login with admin@example.com / password
- Create users, categories, tickets
- Manage tickets through web interface or API

## API Endpoints

- POST /api/auth/login
- GET /api/tickets
- POST /api/tickets
- etc.

## Testing

Run `php artisan test`

## License

MIT
