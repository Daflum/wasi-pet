#!/bin/bash

# Exit on fail
set -e

# Substitute $PORT in nginx config
envsubst '${PORT}' < /etc/nginx/sites-available/default.template > /etc/nginx/sites-available/default

# Run migrations
echo "Running migrations..."
php artisan migrate --force

# Cache configuration, routes, and views
echo "Caching configuration..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Start Nginx and PHP-FPM
echo "Starting Nginx and PHP-FPM..."
php-fpm -D
nginx -t
nginx -g 'daemon off;'
