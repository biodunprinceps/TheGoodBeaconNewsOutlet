#!/bin/bash
set -e

echo "=== Railway Deployment Start ==="
echo "Checking database connection..."

# Run database connection check
php check-db.php

if [ $? -ne 0 ]; then
  echo ""
  echo "TROUBLESHOOTING STEPS:"
  echo "1. Check if PostgreSQL service is added to your Railway project"
  echo "2. Verify DATABASE_URL is set in Railway environment variables"
  echo "3. Ensure PostgreSQL service is started before the app"
  echo "4. Check Railway service logs for PostgreSQL startup issues"
  echo ""
  echo "To add PostgreSQL in Railway:"
  echo "- Go to your project dashboard"
  echo "- Click 'New' -> 'Database' -> 'PostgreSQL'"
  echo "- Railway will automatically set DATABASE_URL"
  echo ""
  exit 1
fi

echo "Database connection successful!"
echo ""

# Run migrations
echo "Running migrations..."
php artisan migrate --force

# Run seeders (only if database is empty)
echo "Running seeders..."
php artisan db:seed --force

# Create admin user if it doesn't exist
echo "Creating admin user..."
php create-admin.php 2>/dev/null || true

# Cache configuration for better performance
echo "Caching configuration..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "Starting web server on port ${PORT:-8000}..."

# Start the web server
php artisan serve --host=0.0.0.0 --port=${PORT:-8000}
