#!/bin/bash
set -e

cd /var/www

# If no .env file exists, create one
if [ ! -f .env ]; then
  cp .env.example .env

  # Replace placeholders
  sed -i "s|APP_NAME=.*|APP_NAME=${APP_NAME:-Xplaza}|g" .env
  sed -i "s|APP_KEY=.*|APP_KEY=${APP_KEY}|g" .env
  sed -i "s|APP_DEBUG=.*|APP_DEBUG=${APP_DEBUG:-true}|g" .env
  sed -i "s|LOG_CHANNEL=.*|LOG_CHANNEL=${LOG_CHANNEL:-stack}|g" .env
  sed -i "s|API_BASE_URL=.*|API_BASE_URL=${API_BASE_URL:-https://api.xplaza.shop/api/v1}|g" .env
  sed -i "s|IMAGE_BASE_URL=.*|IMAGE_BASE_URL=${IMAGE_BASE_URL:-https://images.xplaza.shop/api/v1}|g" .env
  sed -i "s|ADMIN_USERNAME=.*|ADMIN_USERNAME=${ADMIN_USERNAME}|g" .env
  sed -i "s|ADMIN_PASSWORD=.*|ADMIN_PASSWORD=${ADMIN_PASSWORD}|g" .env
fi

# Clear cache
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Finally, run the container
exec "$@"