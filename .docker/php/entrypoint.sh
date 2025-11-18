#!/bin/bash
set -e

# Install PHP dependencies
echo "Installing PHP dependencies..."
composer install

# Create .env file if it doesn't exist
echo "Setting up environment..."
if [ ! -f .env ]; then
    cp .env.example .env

    # Generate application key only on first setup
    echo "Generating application key..."
    php artisan key:generate
fi

# Run migrations
echo "Running migrations..."
php artisan migrate --force

# Install npm dependencies
echo "Installing npm dependencies..."
npm install

# Build assets
echo "Building assets..."
npm run build

# Start PHP-FPM
echo "Starting PHP-FPM..."
exec php-fpm
