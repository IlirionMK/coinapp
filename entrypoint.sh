#!/bin/bash

set -e

echo "Starting service with role: $ROLE"

case "$ROLE" in
  queue)
    echo "Starting Laravel queue worker..."
    php artisan queue:work --tries=3 --timeout=90
    ;;
  scheduler)
    echo "Starting Laravel scheduler..."
    php artisan schedule:work
    ;;
  *)
    echo "Unknown or missing role: $ROLE"
    exec "$@"
    ;;
esac
