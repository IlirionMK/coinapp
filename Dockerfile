FROM php:8.2-fpm

# 1. Установим зависимости ОС и PHP-расширения
RUN apt-get update && apt-get install -y \
    git curl unzip zip \
    libpq-dev libzip-dev libpng-dev \
    libonig-dev libxml2-dev \
    npm nodejs \
    && docker-php-ext-install pdo pdo_pgsql zip

# 2. Установим Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# 3. Настройка рабочей директории
WORKDIR /var/www/html

# 4. Копируем entrypoint (опционально, если он нужен)
# COPY ./docker/entrypoint.sh /usr/local/bin/entrypoint.sh
# RUN chmod +x /usr/local/bin/entrypoint.sh

# 5. Expose порта (не обязательно, nginx проксирует)
EXPOSE 9000
