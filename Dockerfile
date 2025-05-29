FROM php:8.2-fpm-alpine AS base

RUN apt-get update && apt-get install -y \
    git unzip curl libpng-dev libonig-dev libjpeg-dev libfreetype6-dev libzip-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo pdo_mysql zip gd \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

WORKDIR /var/www

COPY . .

FROM node:20-alpine AS nodebuild

WORKDIR /app
COPY . .
RUN npm ci && npm run build

FROM base AS final

# Copy built frontend assets
COPY --from=nodebuild /app/public /var/www/public

# Install composer and dependencies
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer
RUN composer install --no-dev --optimize-autoloader \
    && php artisan config:cache \
    && php artisan route:clear \
    && php artisan view:clear \
    && chmod -R 775 storage bootstrap/cache

EXPOSE 9005
CMD ["php-fpm"]