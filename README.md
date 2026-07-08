# Marketplace Backend

PHP 8.3 / Laravel 13 REST API for the Marketplace project: a public API
(`routes/api_v1.php`, prefix `/api/v1`, clients/buyers), an admin API
(`routes/api_admin.php`, prefix `/api/admin`, staff moderation), and a
partner API (`routes/api_partner.php`, prefix `/api/partner`, sellers who
list products), all registered in `bootstrap/app.php`. Each surface has its
own Sanctum guard (`sanctum`/`admin`/`partner`) and its own Authenticatable
model (`User`/`Admin`/`Partner`) — a client can never authenticate as a
partner or vice versa.

Domain code lives under `app/Domain/{Checkins,Listings,Orders,Partners,Users}`
rather than a flat `app/Http` + `app/Models` split;

Listings belong to partners (`listings.partner_id`), not clients. Partner
accounts self-register via `POST /api/partner/register` but start in a
`pending` status and can't log in until an admin approves them
(`POST /api/admin/partners/{id}/approve`) — mirrors how individual listings
already go through draft → pending_review → active moderation.

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

## Creating users

```bash
docker exec -it marketplace_backend php artisan admin:create   # admin-panel user (App\Domain\Users\Models\Admin)
```

Prompts for name/email/password interactively, or accepts
`--name=`/`--email=`/`--password=` to run non-interactively. There's no
equivalent command for partners — they self-register via
`POST /api/partner/register` and then need an admin to approve them.

## Agentic Development

This app uses [Laravel Boost](https://laravel.com/docs/ai) and has
project-specific conventions in [`CLAUDE.md`](CLAUDE.md) — read that before
making changes here.

## Learning Laravel

- [Laravel documentation](https://laravel.com/docs)
- [Laracasts](https://laracasts.com)

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
