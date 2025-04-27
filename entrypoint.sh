#!/bin/bash

set -e

echo "Starting entrypoint..."

# Install PHP dependencies if vendor folder is missing
if [ ! -d "vendor" ]; then
  echo "Installing composer dependencies..."
  composer install --prefer-dist --no-interaction --no-progress
fi

# Install NPM dependencies if node_modules missing (for vite)
if [ "$ROLE" = "vite" ] && [ ! -d "node_modules" ]; then
  echo "Installing NPM dependencies..."
  npm install
fi

# Run database migrations (only for app role)
if [ "$ROLE" = "app" ]; then
  echo "Running database migrations..."
  php artisan migrate --force
fi

# Run the correct service
case "$ROLE" in
  app)
    php-fpm
    ;;
  vite)
    npm run dev
    ;;
  queue)
    php artisan queue:work
    ;;
  scheduler)
    php artisan schedule:work
    ;;
  *)
    echo "Unknown role: $ROLE"
    exec "$@"
    ;;
esac
