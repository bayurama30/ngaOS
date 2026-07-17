#!/bin/bash
# NgaOS Deployment Script
# Run this script after uploading files to cPanel

echo "=== NgaOS Deployment Script ==="

# 1. Install PHP dependencies
echo "Installing PHP dependencies..."
composer install --optimize-autoloader --no-dev --no-interaction

# 2. Copy .env.production to .env
echo "Setting up environment..."
if [ -f .env.production ]; then
    cp .env.production .env
fi

# 3. Generate application key
echo "Generating application key..."
php artisan key:generate --force

# 4. Cache configuration
echo "Caching configuration..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# 5. Set permissions
echo "Setting permissions..."
chmod -R 755 storage
chmod -R 755 bootstrap/cache

# 6. Run migrations
echo "Running migrations..."
php artisan migrate --force

echo ""
echo "=== Deployment Complete ==="
echo "Don't forget to:"
echo "1. Update .env with your database credentials"
echo "2. Set document root to /public in cPanel"
echo "3. Add cron job: * * * * * cd $(pwd) && php artisan schedule:run >> /dev/null 2>&1"
