#!/bin/bash
set -e

echo "🚀 Configuring Nginx..."

# Substitute $PORT in Nginx config template
envsubst '${PORT}' < /etc/nginx/sites-available/default.template > /etc/nginx/sites-available/default

# Recreate symlink to ensure Nginx uses our config
rm -f /etc/nginx/sites-enabled/default
ln -s /etc/nginx/sites-available/default /etc/nginx/sites-enabled/default

echo "📂 Running Laravel tasks..."
php artisan migrate --force
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan storage:link || true

echo "🐘 Starting PHP-FPM..."
php-fpm -D

echo "🌐 Starting Nginx..."
# Validate config and start in foreground
nginx -t
nginx -g 'daemon off;'
