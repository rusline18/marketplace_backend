# Marketplace Backend

PHP 8.3 / Laravel 13 REST API for the Marketplace project: a public API
(`routes/api_v1.php`, prefix `/api/v1`) and an admin API
(`routes/api_admin.php`, prefix `/api/admin`), both registered in
`bootstrap/app.php`.

Domain code lives under `app/Domain/{Checkins,Listings,Orders,Users}`
rather than a flat `app/Http` + `app/Models` split;

## Stack

- PHP 8.3, Laravel 13, Sanctum
- PostgreSQL 16, Redis, Elasticsearch
- Pest 4 for tests, Larastan for static analysis, Pint for formatting
- Runs in Docker (nginx + php-fpm + postgres + redis + elasticsearch)

## Setup

```bash
cp .env.example .env
docker compose up -d          # nginx, backend (php-fpm), postgres, redis, elasticsearch
docker compose exec backend php artisan key:generate
docker compose exec backend php artisan migrate
```

The API is available at `http://localhost:8000` by default (`NGINX_PORT`).
Check what port your local `.env`/`docker-compose.override.yml` actually
expose it on before pointing `admin`/`frontend` at it.

PHP runs inside the `marketplace_backend` container, not on the host —
prefix `php`/`composer`/`vendor/bin/*` commands accordingly:

```bash
docker exec marketplace_backend php artisan test --compact   # Pest
docker exec marketplace_backend vendor/bin/pint --format agent
docker exec marketplace_backend vendor/bin/phpstan analyse
```

## Agentic Development

This app uses [Laravel Boost](https://laravel.com/docs/ai) and has
project-specific conventions in [`CLAUDE.md`](CLAUDE.md) — read that before
making changes here.

## Learning Laravel

- [Laravel documentation](https://laravel.com/docs)
- [Laracasts](https://laracasts.com)

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
