#!/bin/bash
set -e

# Fix permissions for www-data (excluding .git)
echo "Fixing permissions..."
find /var/www -path /var/www/.git -prune -o -exec chown www-data:www-data {} + || true

# Install PHP dependencies
echo "Installing PHP dependencies..."
su -s /bin/bash www-data -c "composer install"

# Create .env file if it doesn't exist
echo "Setting up environment..."
if [ ! -f .env ]; then
    su -s /bin/bash www-data -c "cp .env.example .env"

    # Generate application key only on first setup
    echo "Generating application key..."
    su -s /bin/bash www-data -c "php artisan key:generate"
else
    # Check if APP_KEY is set
    if ! grep -q "^APP_KEY=.\+$" .env; then
        echo "APP_KEY is not set. Generating application key..."
        su -s /bin/bash www-data -c "php artisan key:generate"
    fi
fi

# Run migrations
echo "Running migrations..."
su -s /bin/bash www-data -c "php artisan migrate --force"

# Install npm dependencies
echo "Installing npm dependencies..."
su -s /bin/bash www-data -c "npm install"

# Build assets
echo "Building assets..."
su -s /bin/bash www-data -c "npm run build"

# Start PHP-FPM
echo "Starting PHP-FPM..."
exec php-fpm
