#!/bin/sh

set -e

cd /var/www/html

echo "🎬 Контейнер стартует с ролью: $APP_ROLE"

case "$APP_ROLE" in
  app)
    echo "🚀 Запуск Laravel APP"

    if [ ! -f artisan ]; then
      echo "🔧 Laravel не найден. Устанавливаем..."
      composer create-project laravel/laravel . --prefer-dist
    fi

    if [ ! -f .env ]; then
      echo "📄 Настройка .env"
      if [ -f .env.docker ]; then
        cp .env.docker .env
      else
        cp .env.example .env
      fi
      php artisan key:generate
    fi

    DB_DRIVER=${DB_DRIVER:-sqlite}
    if [ "$DB_DRIVER" = "sqlite" ]; then
      echo "🎛️ Используем SQLite"
      sed -i 's/^DB_CONNECTION=.*/DB_CONNECTION=sqlite/' .env
      sed -i '/^DB_HOST=/d' .env
      sed -i '/^DB_PORT=/d' .env
      sed -i '/^DB_DATABASE=/d' .env
      echo "DB_DATABASE=${PWD}/database/database.sqlite" >> .env
      mkdir -p database && touch database/database.sqlite
    else
      echo "🔗 Используем PostgreSQL"
      sed -i 's/^DB_CONNECTION=.*/DB_CONNECTION=pgsql/' .env
      sed -i 's/^DB_HOST=.*/DB_HOST=postgres/' .env
      sed -i 's/^DB_PORT=.*/DB_PORT=5432/' .env
      sed -i 's/^DB_DATABASE=.*/DB_DATABASE=coinapp/' .env
      sed -i 's/^DB_USERNAME=.*/DB_USERNAME=coinuser/' .env
      sed -i 's/^DB_PASSWORD=.*/DB_PASSWORD=secret/' .env
    fi

    chmod -R 775 storage bootstrap/cache database || true
    chown -R www-data:www-data storage bootstrap/cache database || true

    echo "📦 Установка PHP-зависимостей..."
    composer install --no-interaction || true

    php artisan config:clear
    php artisan migrate --force || true
    php artisan storage:link || true
    php artisan cache:clear || true

    echo "✅ Laravel готов!"
    exec php-fpm
    ;;

  vite)
    echo "🎨 Запуск Vite"
    cd /var/www/html

    if [ ! -d node_modules ]; then
      echo "📦 Установка NPM-зависимостей (впервые)..."
      npm install
    else
      echo "📦 Проверка наличия vue и @vitejs/plugin-vue..."
      if ! npm list vue >/dev/null 2>&1 || ! npm list @vitejs/plugin-vue >/dev/null 2>&1; then
        echo "➕ Установка недостающих пакетов..."
        npm install vue @vitejs/plugin-vue
      else
        echo "✅ vue и @vitejs/plugin-vue уже установлены"
      fi
    fi

    echo "🚀 Запуск Vite Dev Server"
    exec npm run dev
    ;;

  queue)
    echo "📩 Запуск Laravel Queue"
    exec php artisan queue:work --verbose --tries=3 --timeout=90
    ;;

  scheduler)
    echo "⏰ Запуск Laravel Scheduler"
    while true; do
      php artisan schedule:run --verbose --no-interaction &
      sleep 60
    done
    ;;

  *)
    echo "⚠️ Неизвестная роль: $APP_ROLE"
    exec "$@"
    ;;
esac
