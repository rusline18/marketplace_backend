FROM php:8.3-fpm AS base

RUN apt-get update && apt-get install -y \
    git unzip libzip-dev libpng-dev libonig-dev libpq-dev \
    && docker-php-ext-install pdo_pgsql zip bcmath mbstring exif pcntl gd

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

EXPOSE 9000
CMD ["php-fpm"]

# ---- development image: source is bind-mounted in, deps installed with dev requirements ----
FROM base AS dev

COPY composer.json composer.lock ./
RUN composer install --no-interaction --prefer-dist --no-scripts

COPY . .

# ---- production image: source baked in, no dev deps, optimized autoloader ----
# Config/route/view caching needs the real runtime .env (DB creds, APP_KEY), which is
# injected at container start via env_file - not baked into the image. Run it as part
# of the deploy step (see docker-compose.prod.yml command) instead of at build time.
FROM base AS prod

COPY . .

RUN composer install --no-interaction --prefer-dist --no-dev --optimize-autoloader

# ---- production nginx image: bakes in the public/ assets built above, since prod has no bind mount ----
FROM nginx:alpine AS nginx-prod

COPY docker/nginx.conf /etc/nginx/conf.d/default.conf
COPY --from=prod /var/www/html/public /var/www/html/public
