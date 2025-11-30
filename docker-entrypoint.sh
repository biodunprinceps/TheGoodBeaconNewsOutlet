#!/bin/bash
set -e

echo "Running migrations..."
php artisan migrate --force

echo "Running seeders..."
php artisan db:seed --force

echo "Creating admin user..."
php create-admin.php

echo "Starting Laravel server..."
exec php artisan serve --host=0.0.0.0 --port=8080
