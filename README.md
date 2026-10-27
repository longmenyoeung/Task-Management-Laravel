# Task-Management-Laravel

A task management REST API built with **Laravel 13**, **Laravel Sanctum** and **Vite + Tailwind CSS 4**.
Authenticated users can group their work into categories and track individual tasks with a priority
and a due date.

## Features

- Token based authentication with Laravel Sanctum (`POST /api/auth/register` returns a plain text token).
- `Category` and `Task` Eloquent models backed by foreign-keyed migrations (`user_id`, `category_id`, cascade on delete).
- Task fields: `title`, `description` (nullable), `priority` (`low` | `medium` | `high`, default `medium`) and `due_date`.
- Every `api/*` request (or any request asking for JSON) is rendered as JSON by the exception handler.
- Health check endpoint available at `/up`.

## Requirements

- PHP 8.3 or newer
- Composer 2
- Node.js 20 or newer with npm

## Installation

```bash
# 1. Install PHP dependencies
composer install

# 2. Create the environment file and application key
cp .env.example .env
php artisan key:generate

# 3. Run the migrations
php artisan migrate

# 4. Install and build the front-end assets
npm install
npm run build
```

Start the development server:

```bash
php artisan serve
```

Or run the server, queue listener, log viewer and Vite together:

```bash
composer dev
```

## API reference

| Method | Endpoint             | Auth | Description                                 |
| ------ | -------------------- | ---- | ------------------------------------------- |
| POST   | `/api/auth/register` | No   | Register a user and receive an API token.   |
| GET    | `/api/user`          | Yes  | Return the authenticated user.              |

Authenticated requests must send the token in the `Authorization` header:

```http
Authorization: Bearer <token>
Accept: application/json
```

### Example: register

```bash
curl -X POST http://localhost:8000/api/auth/register \
  -H "Accept: application/json" \
  -H "Content-Type: application/json" \
  -d '{"name":"Jane Doe","email":"jane@example.com","password":"password123"}'
```

```json
{
  "success": true,
  "message": "User created successfully.",
  "user": { "id": 1, "name": "Jane Doe", "email": "jane@example.com" },
  "token": "1|plain-text-sanctum-token"
}
```

## Project structure

```
app/
├── Http/Controllers/
│   ├── AuthController.php      # register endpoint
│   ├── CategoryController.php  # scaffolded
│   └── TaskController.php      # scaffolded
└── Models/
    ├── Category.php
    ├── Task.php
    └── User.php
database/migrations/            # users, cache, jobs, categories, tasks, personal access tokens
routes/
├── api.php                     # register + authenticated user endpoints
├── console.php
└── web.php
```

## Testing

```bash
composer test
```

## Code style

```bash
./vendor/bin/pint
```

---

Built on the [Laravel](https://laravel.com) framework. The sections below are the framework's default documentation.

## About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

- [Simple, fast routing engine](https://laravel.com/docs/routing).
- [Powerful dependency injection container](https://laravel.com/docs/container).
- Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
- Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
- Database agnostic [schema migrations](https://laravel.com/docs/migrations).
- [Robust background job processing](https://laravel.com/docs/queues).
- [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, powerful, and provides tools required for large, robust applications.

## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework.

In addition, [Laracasts](https://laracasts.com) contains thousands of video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

You can also watch bite-sized lessons with real-world projects on [Laravel Learn](https://laravel.com/learn), where you will be guided through building a Laravel application from scratch while learning PHP fundamentals.

## Agentic Development

Laravel's predictable structure and conventions make it ideal for AI coding agents like Claude Code, Cursor, and GitHub Copilot. Install [Laravel Boost](https://laravel.com/docs/ai) to supercharge your AI workflow:

```bash
composer require laravel/boost --dev

php artisan boost:install
```

Boost provides your agent 15+ tools and skills that help agents build Laravel applications while following best practices.

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
