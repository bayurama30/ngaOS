#!/bin/bash
# NgaOS Deployment Script
# Run this script after uploading files to cPanel

echo "=== NgaOS Deployment Script ==="

# 1. Create required directories
echo "Creating required directories..."
mkdir -p storage/framework/cache/data
mkdir -p storage/framework/sessions
mkdir -p storage/framework/views
mkdir -p storage/logs
mkdir -p storage/app/public
mkdir -p bootstrap/cache

# 2. Set permissions BEFORE artisan commands
echo "Setting permissions..."
chmod -R 777 storage
chmod -R 777 bootstrap/cache

# 3. Install PHP dependencies
echo "Installing PHP dependencies..."
composer install --optimize-autoloader --no-dev --no-interaction

# 4. Copy .env.production to .env
echo "Setting up environment..."
if [ -f .env.production ]; then
    cp .env.production .env
    echo ".env.production copied to .env"
elif [ -f .env.example ]; then
    cp .env.example .env
    echo ".env.example copied to .env"
fi

# 5. Generate application key
echo "Generating application key..."
php artisan key:generate --force

# 6. Storage link & cache
echo "Creating storage link..."
ln -sf ../storage/app/public public/storage

echo "Caching configuration..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# 6. Run migrations
echo "Running migrations..."
php artisan migrate --force

echo ""
echo "=== Deployment Complete ==="
echo "Don't forget to:"
echo "1. Update .env with your database credentials"
echo "2. Set document root to /public in cPanel"
echo "3. Add cron job: * * * * * cd $(pwd) && php artisan schedule:run >> /dev/null 2>&1"
