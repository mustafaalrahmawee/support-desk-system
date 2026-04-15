# ─────────────────────────────────────────────────────────────────────────────
# Stage 1: Composer dependencies
# ─────────────────────────────────────────────────────────────────────────────
FROM composer:2.8 AS composer

WORKDIR /app
COPY composer.json composer.lock ./
RUN composer install \
    --no-scripts \
    --no-autoloader \
    --ignore-platform-reqs \
    --prefer-dist

COPY . .
RUN composer dump-autoload --optimize

# ─────────────────────────────────────────────────────────────────────────────
# Stage 2: Runtime image
# ─────────────────────────────────────────────────────────────────────────────
FROM php:8.4-cli-alpine

LABEL maintainer="support-desk-system"

# Install system dependencies and PHP extensions
RUN apk add --no-cache \
        bash \
        libpq-dev \
        libzip-dev \
        zip \
        unzip \
        git \
        curl \
    && docker-php-ext-install \
        pdo \
        pdo_pgsql \
        pgsql \
        zip \
        opcache \
        pcntl

# Copy PHP production config
COPY --from=composer /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# Copy app with vendor from composer stage
COPY --from=composer /app /var/www/html

# Create necessary directories and set permissions
RUN mkdir -p storage/framework/{sessions,views,cache} \
             storage/logs \
             bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache

# Copy entrypoint script
COPY docker-entrypoint.sh /usr/local/bin/docker-entrypoint.sh
RUN chmod +x /usr/local/bin/docker-entrypoint.sh

EXPOSE 8000

ENTRYPOINT ["/usr/local/bin/docker-entrypoint.sh"]
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"]
