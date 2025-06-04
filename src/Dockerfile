FROM php:8.2-fpm

RUN apt-get update && apt-get install -y \
    git curl unzip zip \
    libpq-dev libzip-dev libpng-dev \
    libonig-dev libxml2-dev netcat-openbsd \
    && docker-php-ext-install pdo pdo_pgsql zip \
    && pecl install redis \
    && docker-php-ext-enable redis

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

COPY . .

RUN composer install --no-dev --optimize-autoloader --no-interaction

RUN php artisan config:clear \
 && php artisan config:cache \
 && php artisan route:clear \
 && php artisan route:cache \
 && php artisan view:clear \
 && php artisan view:cache

RUN chown -R www-data:www-data storage bootstrap/cache

CMD ["php-fpm"]
