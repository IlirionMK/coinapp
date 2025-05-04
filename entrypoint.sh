#!/bin/bash
set -e

echo "Starting service with role: $ROLE"

case "$ROLE" in
  app)
    echo "Installing PHP dependencies if missing..."
    if [ ! -d "vendor" ]; then
      composer install --no-interaction --prefer-dist
    fi

    echo "Waiting for PostgreSQL to be ready..."
    until nc -z postgres 5432; do
      sleep 1
    done
    echo "PostgreSQL is ready."

    echo "Running database migrations..."
    php artisan migrate --force

    echo "Syncing coins from CoinGecko..."
    php artisan coins:sync

    echo "Clearing and caching config/routes/views..."
    php artisan config:clear
    php artisan config:cache
    php artisan route:clear
    php artisan route:cache
    php artisan view:clear
    php artisan view:cache

    echo "Starting PHP-FPM server..."
    exec php-fpm
    ;;

  queue)
    echo "Starting Laravel queue worker..."
    php artisan queue:work --tries=3 --timeout=90
    ;;

  scheduler)
    echo "Starting Laravel scheduler..."
    php artisan schedule:work
    ;;

  *)
    echo "Unknown or missing role: $ROLE. Running default command: $@"
    exec "$@"
    ;;
esac
