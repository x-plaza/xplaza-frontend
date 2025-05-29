# ---- PHP base image ----
FROM php:8.2-fpm-alpine AS base

# Install PHP dependencies using Alpine's apk
RUN apk update && apk add --no-cache \
    git \
    unzip \
    curl \
    libpng-dev \
    libjpeg-turbo-dev \
    freetype-dev \
    libzip-dev \
    oniguruma-dev \
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
RUN npm ci && npm run prod

# ---- Final stage ----
FROM base AS final

# Copy built frontend assets
COPY --from=nodebuild /app/public /var/www/public

# Ensure directories exist and are writable before composer install
RUN mkdir -p bootstrap/cache storage \
    && chmod -R 775 bootstrap/cache storage

# Install composer dependencies and run artisan commands
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer
RUN composer install --no-dev --optimize-autoloader \
    && php artisan config:cache \
    && php artisan route:clear \
    && php artisan view:clear

EXPOSE 9005
CMD ["php-fpm"]