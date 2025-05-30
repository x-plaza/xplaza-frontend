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

# Ensure Laravel directories exist and are writable
RUN mkdir -p bootstrap/cache storage/framework/views storage/logs storage/framework/sessions \
    && chmod -R 775 bootstrap/cache storage

# Install Composer dependencies
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer
RUN composer install --no-interaction --prefer-dist --optimize-autoloader

# Copy entrypoint script
COPY docker-entrypoint.sh /usr/local/bin/docker-entrypoint.sh
RUN chmod +x /usr/local/bin/docker-entrypoint.sh

EXPOSE 8008

ENTRYPOINT ["docker-entrypoint.sh"]
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8008"]