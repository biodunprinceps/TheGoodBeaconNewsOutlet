#!/bin/bash

# Run migrations
php artisan migrate --force

# Run seeders (only if database is empty)
php artisan db:seed --force

# Create admin user if it doesn't exist
php create-admin.php 2>/dev/null || true

# Cache configuration for better performance
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Start the web server
php artisan serve --host=0.0.0.0 --port=${PORT:-8000}
