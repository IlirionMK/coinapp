FROM php:8.2-fpm

# Установим системные зависимости и расширения PHP
RUN apt-get update && apt-get install -y \
    git curl unzip zip \
    libpq-dev libzip-dev libpng-dev \
    libonig-dev libxml2-dev \
    && docker-php-ext-install pdo pdo_pgsql zip

# Установка расширения Redis через PECL
RUN pecl install redis && docker-php-ext-enable redis

# Установка Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Установка рабочей директории
WORKDIR /var/www/html

# Копируем только файлы composer для кэширования vendor
COPY src/composer.json src/composer.lock ./

# Установка зависимостей PHP (кэшируется)
RUN composer install --prefer-dist --no-dev --no-autoloader --no-scripts || true

# Копируем всё приложение
COPY ./src ./

# Финальный composer install
RUN composer install --prefer-dist --no-dev --no-interaction --optimize-autoloader

EXPOSE 9000
