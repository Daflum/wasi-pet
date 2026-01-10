#!/bin/bash

# Exit on fail
set -e

# Cache configuration, routes, and views
echo "Caching configuration..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Start Nginx and PHP-FPM
echo "Starting Nginx and PHP-FPM..."
service nginx start
php-fpm
