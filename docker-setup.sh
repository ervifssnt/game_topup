#!/bin/bash
set -e

echo "🔧 Setting up Laravel Game Topup - Docker Environment"
echo ""

# Check if Docker is running
if ! docker ps > /dev/null 2>&1; then
    echo "❌ Docker is not running. Please start Docker first."
    exit 1
fi

# Check if .env.docker exists
if [ ! -f .env.docker ]; then
    echo "📋 Creating .env.docker from template..."
    cp .env.docker.example .env.docker
    echo "⚠️  IMPORTANT: Edit .env.docker and change the default passwords!"
    echo ""
    read -p "Press Enter after editing .env.docker to continue..."
fi

echo "🐳 Starting Docker containers..."
docker compose up -d

echo "⏳ Waiting for MySQL to be ready..."
sleep 15

echo "🔑 Generating application key..."
docker compose exec app php artisan key:generate --force

echo "📦 Installing dependencies..."
docker compose exec app composer install --no-interaction

echo "🗃️  Running database migrations..."
docker compose exec app php artisan migrate --force

echo "🌱 Seeding database..."
docker compose exec app php artisan db:seed --force

echo "🔧 Setting permissions..."
echo "🌐 Starting nginx..."
docker compose exec app nginx
docker compose exec app chmod -R 775 storage bootstrap/cache
docker compose exec app chown -R www-data:www-data storage bootstrap/cache


echo ""
echo "✅ Setup complete!"
echo ""
echo "🌐 Application URLs:"
echo "   Main App:    http://localhost:8000"
echo "   phpMyAdmin:  http://localhost:8080"
echo ""
echo "👤 Default Admin Credentials:"
echo "   Email:    admin@test.com"
echo "   Password: password"
echo ""
echo "⚠️  Security Note: Change default passwords before production use!"
