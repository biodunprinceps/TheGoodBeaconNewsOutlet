#!/bin/bash

# Run migrations
php artisan migrate --force

# Cache configuration for better performance
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Start the web server
php artisan serve --host=0.0.0.0 --port=${PORT:-8000}
