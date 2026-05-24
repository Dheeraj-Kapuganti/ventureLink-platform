#!/bin/sh

# Fail immediately if any command fails
set -e

echo "🚀 Starting ventureLink initialization..."

# Copy .env if not exists
if [ ! -f .env ]; then
    echo "📄 Creating .env file from .env.example..."
    cp .env.example .env
    # Change DB_HOST to 'mongodb' to match the docker-compose service name
    sed -i 's/DB_HOST=127.0.0.1/DB_HOST=mongodb/g' .env
fi

# Install PHP dependencies
if [ ! -f vendor/autoload.php ]; then
    echo "📦 Installing Composer dependencies..."
    composer install --no-interaction --prefer-dist
fi

# Generate application key if not set
if [ -z "$(grep APP_KEY= .env | cut -d= -f2)" ]; then
    echo "🔑 Generating application key..."
    php artisan key:generate
fi

# Install Node dependencies
if [ ! -d node_modules/vite ]; then
    echo "💻 Installing NPM packages..."
    npm install
fi

# Seed database
echo "🗄️ Seeding MongoDB database..."
php artisan db:seed --force
php artisan db:seed --class=TempSeeder --force

echo "🔥 Launching ventureLink dev servers..."
# Start Laravel and Vite in parallel binding to 0.0.0.0 for external docker access
exec npx concurrently -c "#93c5fd,#c4b5fd" \
  "php artisan serve --host=0.0.0.0 --port=8000" \
  "npm run dev -- --host 0.0.0.0" \
  --names="laravel,vite"
