#!/bin/bash
set -e

cd /var/www/html

# Render injects RENDER_EXTERNAL_HOSTNAME; use it when APP_URL was not set explicitly.
if [ -z "$APP_URL" ] && [ -n "$RENDER_EXTERNAL_HOSTNAME" ]; then
  export APP_URL="https://$RENDER_EXTERNAL_HOSTNAME"
fi

# Writable dirs (fresh container / disk).
mkdir -p storage/app/public storage/framework/cache storage/framework/sessions storage/framework/views storage/logs bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache

# Fresh checkout needs the public/storage symlink (no-op when using R2).
php artisan storage:link --force || true

# Bake config/routes/views (env is present at boot time on Render).
php artisan config:cache
php artisan route:cache
php artisan view:cache

# First deploy needs tables; set MIGRATE_ON_DEPLOY=false to skip.
if [ "${MIGRATE_ON_DEPLOY:-true}" = "true" ]; then
  php artisan migrate --force
fi

exec apache2-foreground
