# ---- PHP base image ----
FROM php:8.2-cli-alpine AS base

RUN apk update && apk add --no-cache \
    git \
    unzip \
    curl \
    libpng-dev \
    libjpeg-turbo-dev \
    freetype-dev \
    libzip-dev \
    oniguruma-dev \
    bash \
    php82-pecl-xdebug \
    && docker-php-ext-configure gd \
        --with-freetype=/usr/include/ \
        --with-jpeg=/usr/include/ \
    && docker-php-ext-install pdo pdo_mysql zip gd

WORKDIR /var/www
COPY . .

# ---- Node.js build stage ----
FROM node:20-alpine AS nodebuild

WORKDIR /app
COPY . .
RUN npm ci && npm run dev

# ---- Final stage ----
FROM base AS final

# Copy built frontend assets
COPY --from=nodebuild /app/public /var/www/public

# Ensure directories exist and writable
RUN mkdir -p bootstrap/cache storage/framework/views storage/logs storage/framework/sessions \
    && chmod -R 775 bootstrap/cache storage

# Install composer dependencies
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer
RUN composer install

# Set permissions
RUN chown -R www-data:www-data storage bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache

# Copy entrypoint script
COPY docker-entrypoint.sh /usr/local/bin/docker-entrypoint.sh
RUN chmod +x /usr/local/bin/docker-entrypoint.sh

USER www-data

EXPOSE 8008

# Use entrypoint script instead of CMD directly
ENTRYPOINT ["docker-entrypoint.sh"]
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8008"]