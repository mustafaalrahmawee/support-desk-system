#!/bin/bash
set -e

echo "─────────────────────────────────────────────────"
echo " Support Desk API – Container Startup"
echo "─────────────────────────────────────────────────"

# Generate app key if not set
if [ -z "$APP_KEY" ]; then
  echo "[INFO] Generating application key..."
  php artisan key:generate --force
fi

# Wait for DB is handled by Docker healthcheck, but add a small guard
echo "[INFO] Running migrations..."
php artisan migrate --force --no-interaction

echo "[INFO] Caching config..."
php artisan config:cache || true
php artisan route:cache  || true

echo "[INFO] Starting Laravel server on 0.0.0.0:8000"
exec "$@"
