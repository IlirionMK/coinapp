#!/bin/bash

set -e

echo "Starting service with role: $ROLE"

case "$ROLE" in
  app)
    echo "Fixing permissions for Laravel..."
    chmod -R 777 storage bootstrap/cache

    echo "Running database migrations..."
    php artisan migrate --force

    echo "Checking if coins table exists..."
    if php artisan db:table-exists coins; then
      echo "Syncing coins from CoinGecko..."
      php artisan coins:sync
    else
      echo "Coins table not found, skipping sync!"
    fi

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
