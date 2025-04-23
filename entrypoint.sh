#!/bin/sh

set -e

cd /var/www/html

echo "Container starting with role: $APP_ROLE"

case "$APP_ROLE" in
  app)
    echo "Starting Laravel application..."

    if [ ! -f artisan ]; then
      echo "Laravel not found. Creating new project..."
      composer create-project laravel/laravel . --prefer-dist
    fi

    if [ ! -f .env ]; then
      echo "Setting up .env"
      if [ -f .env.docker ]; then
        cp .env.docker .env
      else
        cp .env.example .env
      fi
      php artisan key:generate
    fi

    DB_DRIVER=${DB_DRIVER:-sqlite}
    if [ "$DB_DRIVER" = "sqlite" ]; then
      echo "Using SQLite"
      sed -i 's/^DB_CONNECTION=.*/DB_CONNECTION=sqlite/' .env
      sed -i '/^DB_HOST=/d' .env
      sed -i '/^DB_PORT=/d' .env
      sed -i '/^DB_DATABASE=/d' .env
      echo "DB_DATABASE=${PWD}/database/database.sqlite" >> .env
      mkdir -p database && touch database/database.sqlite
    else
      echo "Using PostgreSQL"
      sed -i 's/^DB_CONNECTION=.*/DB_CONNECTION=pgsql/' .env
      sed -i 's/^DB_HOST=.*/DB_HOST=postgres/' .env
      sed -i 's/^DB_PORT=.*/DB_PORT=5432/' .env
      sed -i 's/^DB_DATABASE=.*/DB_DATABASE=coinapp/' .env
      sed -i 's/^DB_USERNAME=.*/DB_USERNAME=coinuser/' .env
      sed -i 's/^DB_PASSWORD=.*/DB_PASSWORD=secret/' .env
    fi

    chmod -R 775 storage bootstrap/cache database || true
    chown -R www-data:www-data storage bootstrap/cache database || true

    echo "Installing PHP dependencies..."
    composer install --no-interaction || true

    php artisan config:clear
    php artisan migrate --force || true
    php artisan storage:link || true
    php artisan cache:clear || true

    echo "Laravel is ready."
    exec php-fpm
    ;;

  vite)
    echo "Starting Vite"
    cd /var/www/html

    if [ ! -d node_modules ]; then
      echo "Installing NPM dependencies (initial)..."
      npm install
    else
      echo "Checking for vue and @vitejs/plugin-vue..."
      if ! npm list vue >/dev/null 2>&1 || ! npm list @vitejs/plugin-vue >/dev/null 2>&1; then
        echo "Installing missing packages..."
        npm install vue @vitejs/plugin-vue
      else
        echo "vue and @vitejs/plugin-vue already installed"
      fi
    fi

    echo "Starting Vite Dev Server"
    exec npm run dev
    ;;

  queue)
    echo "Starting Laravel queue worker"
    exec php artisan queue:work --verbose --tries=3 --timeout=90
    ;;

  scheduler)
    echo "Starting Laravel scheduler"
    while true; do
      php artisan schedule:run --verbose --no-interaction &
      sleep 60
    done
    ;;

  *)
    echo "Unknown role: $APP_ROLE"
    exec "$@"
    ;;
esac
