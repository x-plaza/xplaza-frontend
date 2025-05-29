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
RUN mkdir -p bootstrap/cache storage/framework/views storage/logs \
    && chmod -R 775 bootstrap/cache storage

# Install composer dependencies and run artisan commands
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer
RUN composer install --no-dev --optimize-autoloader
RUN php artisan config:cache
RUN php artisan route:clear
RUN php artisan view:clear
RUN php artisan optimize
RUN php artisan storage:link

# Set permissions for storage and bootstrap/cache directories
RUN chown -R www-data:www-data storage bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache

# Set the user to www-data for running the application
USER www-data

# Expose the port that PHP-FPM listens on       
EXPOSE 9005
CMD ["php-fpm"]