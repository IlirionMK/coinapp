FROM php:8.2-fpm

# Install system dependencies and PHP extensions
RUN apt-get update && apt-get install -y \
    git curl unzip zip \
    libpq-dev libzip-dev libpng-dev \
    libonig-dev libxml2-dev \
    && docker-php-ext-install pdo pdo_pgsql zip

# Install Redis extension via PECL
RUN pecl install redis && docker-php-ext-enable redis

# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www/html

# Copy only composer files for dependency caching
COPY src/composer.json src/composer.lock ./

# Install PHP dependencies (cached layer)
RUN composer install --prefer-dist --no-dev --no-autoloader --no-scripts || true

# Copy the entire application
COPY ./src ./

# Final Composer install
RUN composer install --prefer-dist --no-dev --no-interaction --optimize-autoloader

EXPOSE 9000
