#!/bin/bash

echo "🔧 Setting up Laravel project..."

# 1. Check for .env file
if [ ! -f .env ]; then
    echo "📄 Creating .env file from .env.example..."
    cp .env.example .env
else
    echo "✅ .env already exists"
fi

# 2. Install PHP dependencies
echo "📦 Installing PHP dependencies using Composer..."
composer install

# 3. Generate application key
echo "🔑 Generating application key..."
php artisan key:generate

# 4. Set correct permissions
echo "🛠️ Setting directory permissions for storage and bootstrap/cache..."
chmod -R 775 storage
chmod -R 775 bootstrap/cache

# 5. Create necessary storage folders if they don't exist
echo "📁 Ensuring storage directories exist..."
mkdir -p storage/framework/sessions
mkdir -p storage/framework/views
mkdir -p storage/framework/cache

# 7. Install npm dependencies and build assets (if using frontend assets)
if [ -f package.json ]; then
    echo "📦 Installing JavaScript dependencies..."
    rm -rf node_modules package-lock.json
    npm install

    echo "⚙️ Building front-end assets..."
    npm run dev
fi

# 8. Start the Laravel development server
read -p "Do you want to start the Laravel dev server now? (y/n) " start_server
if [ "$start_server" == "y" ]; then
    php artisan serve
fi

echo "✅ Laravel project setup complete!"