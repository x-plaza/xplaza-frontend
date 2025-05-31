#!/bin/bash

echo "🔧 Setting up Laravel project..."

# 1. Check for .env file
if [ ! -f .env ]; then
    echo "📄 Creating .env file from .env.example..."
    cp .env.example .env
else
    echo "✅ .env already exists"
fi

# 2. Create necessary folders if they don't exist
echo "📁 Ensuring necessary directories exist..."
mkdir -p storage
mkdir -p storage/framework/sessions
mkdir -p storage/framework/views
mkdir -p storage/framework/cache
mkdir -p bootstrap/cache

# 3. Set correct permissions
echo "🛠️ Setting directory permissions for storage and bootstrap/cache..."
chmod -R 775 storage
chmod -R 775 bootstrap/cache

# 4. Install PHP dependencies
echo "📦 Installing PHP dependencies using Composer..."
if ! command -v php &> /dev/null; then
    echo "❌ PHP is not installed. Please install PHP first."
    exit 1
fi
if ! command -v composer &> /dev/null; then
    echo "❌ Composer is not installed. Please install Composer first."
    exit 1
fi
rm -rf composer.lock
composer install

# 5. Generate application key
echo "🔑 Generating application key..."
if ! command -v php artisan &> /dev/null; then
    echo "❌ Artisan is not found. Make sure you are in the Laravel project directory."
    exit 1
fi
php artisan key:generate

# 6. Install npm dependencies and build assets (if using frontend assets)
if [ -f package.json ]; then
    echo "📦 Installing JavaScript dependencies..."
    rm -rf node_modules package-lock.json
    npm install

    echo "⚙️ Building front-end assets..."
    npm run dev
fi

# 7. Start the Laravel development server
read -p "Do you want to start the Laravel dev server now? (y/n) " start_server
if [ "$start_server" == "y" ]; then
    echo "🚀 Starting Laravel development server..."
    echo "🛠️ Make sure your .env has all the properties set properly!"
    php artisan serve
fi

echo "✅ Laravel project setup complete!"