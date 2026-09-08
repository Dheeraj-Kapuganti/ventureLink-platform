#!/bin/sh

set -e

echo "🚀 Starting VentureLink..."

# Clear and rebuild Laravel caches
php artisan config:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "✅ VentureLink is ready!"

# Start Laravel
exec php artisan serve --host=0.0.0.0 --port=${PORT:-8000}