FROM php:8.4-fpm-bookworm

# Set working directory
WORKDIR /var/www

# Define build arguments for UID/GID
ARG UID=1000
ARG GID=1000

# Update package manager and modify www-data user
RUN apt-get update && apt-get install -y --no-install-recommends \
    curl \
    git \
    unzip \
    nano \
    ca-certificates \
    libfreetype6 \
    libjpeg62-turbo \
    libzip4 \
    && groupmod -g ${GID} www-data \
    && usermod -u ${UID} -g ${GID} www-data

# Configure git to allow /var/www as a safe directory
RUN git config --system --add safe.directory /var/www

# Install Node.js 24 from NodeSource repository
RUN curl -fsSL https://deb.nodesource.com/setup_24.x | bash - && \
    apt-get install -y --no-install-recommends nodejs

# Install build dependencies temporarily
RUN apt-get install -y --no-install-recommends \
    autoconf \
    g++ \
    make \
    libpng-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    libzip-dev \
    libcurl4-openssl-dev \
    libonig-dev \
    pkg-config \
    libicu-dev \
    libsodium-dev \
    zlib1g-dev

# Configure and install PHP extensions
RUN docker-php-ext-configure gd --with-freetype --with-jpeg
RUN docker-php-ext-install \
    pdo \
    pdo_mysql \
    mbstring \
    zip \
    bcmath \
    gd \
    intl \
    opcache \
    sockets \
    sodium

# Install PECL extensions
RUN pecl install mongodb redis pcov && \
    docker-php-ext-enable mongodb redis opcache pcov

# Install minimal Playwright system dependencies
RUN apt-get install -y --no-install-recommends \
    fonts-liberation \
    libnss3 \
    libxss1 \
    libatk1.0-0 \
    libatk-bridge2.0-0 \
    libcups2 \
    libxkbcommon0 \
    libpango-1.0-0 \
    libgbm1 \
    libxdamage1 \
    libxfixes3 \
    libxrandr2 \
    libasound2

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Set environment variables for cache directories
ENV npm_config_cache=/var/cache/.npm \
    PLAYWRIGHT_BROWSERS_PATH=/var/cache/.playwright \
    COMPOSER_CACHE_DIR=/var/cache/.composer-cache \
    COMPOSER_HOME=/var/cache/.composer-home \
    HOME=/var/cache/.home

# Create cache directories and set permissions
RUN mkdir -p /var/cache/.npm /var/cache/.npm-cache /var/cache/.playwright /var/cache/.composer-cache /var/cache/.composer-home /var/cache/.home && \
    chown -R www-data:www-data /var/cache /var/www

# Copy entrypoint script
COPY --chown=www-data:www-data .docker/php/entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

# Copy PHP configuration
COPY .docker/php/php.ini /usr/local/etc/php/conf.d/app.ini

# Switch to www-data user
USER www-data

# Copy only composer files for dependency installation
COPY --chown=www-data:www-data composer.json composer.lock* ./

# Install PHP dependencies without scripts
RUN composer install --optimize-autoloader --no-dev --no-scripts

# Copy package files and install npm dependencies
COPY --chown=www-data:www-data package.json package-lock.json* ./
RUN npm ci

# Switch to root to install system dependencies for Playwright
USER root
RUN npx playwright install-deps

# Switch back to www-data to install browsers
USER www-data
RUN npx playwright install

# Expose port
EXPOSE 9000

# Start PHP-FPM via entrypoint
ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]
