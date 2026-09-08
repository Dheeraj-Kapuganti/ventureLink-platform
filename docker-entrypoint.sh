#!/bin/sh

set -e

echo "🚀 Starting VentureLink..."

# Install PHP dependencies
composer install --no-interaction --prefer-dist --optimize-autoloader

# Install frontend dependencies
npm install

# Build frontend assets
npm run build

# Generate APP_KEY if it doesn't exist
if [ -z "$APP_KEY" ]; then
    echo "🔑 Generating application key..."
    php artisan key:generate --force
fi

# Cache Laravel configuration
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "✅ VentureLink is ready!"

# Start Laravel
exec php artisan serve --host=0.0.0.0 --port=${PORT:-8000}