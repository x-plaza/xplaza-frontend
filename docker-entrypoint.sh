#!/bin/bash
set -e

# Switch to root temporarily to write files and set permissions
if [ "$(id -u)" != "0" ]; then
  exec sudo "$0" "$@"
fi

cd /var/www

# If no .env file exists, create one from example
if [ ! -f .env ]; then
  cp .env.example .env

  # Replace placeholders with environment variables passed by Coolify
  sed -i "s|APP_NAME=.*|APP_NAME=${APP_NAME:-Xplaza}|g" .env
  sed -i "s|APP_KEY=.*|APP_KEY=${APP_KEY}|g" .env
  sed -i "s|APP_DEBUG=.*|APP_DEBUG=${APP_DEBUG:-true}|g" .env
  sed -i "s|LOG_CHANNEL=.*|LOG_CHANNEL=${LOG_CHANNEL:-stack}|g" .env
  sed -i "s|API_BASE_URL=.*|API_BASE_URL=${API_BASE_URL:-https://api.xplaza.shop/api/v1}|g" .env
  sed -i "s|IMAGE_BASE_URL=.*|IMAGE_BASE_URL=${IMAGE_BASE_URL:-https://images.xplaza.shop/api/v1}|g" .env
  sed -i "s|ADMIN_USERNAME=.*|ADMIN_USERNAME=${ADMIN_USERNAME}|g" .env
  sed -i "s|ADMIN_PASSWORD=.*|ADMIN_PASSWORD=${ADMIN_PASSWORD}|g" .env
fi

# Run artisan cache clear commands as www-data user (Laravel recommends clearing after env change)
su-exec www-data php artisan config:clear
su-exec www-data php artisan route:clear
su-exec www-data php artisan view:clear

# Fix permissions (optional, safe)
chown -R www-data:www-data storage bootstrap/cache

# Finally, run the container CMD
exec "$@"
