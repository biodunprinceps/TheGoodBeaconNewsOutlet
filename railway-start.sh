#!/bin/bash

echo "=== Railway Deployment Start ==="

# Function to wait for database
wait_for_db() {
  echo "Waiting for database to be ready..."
  max_tries=30
  count=0
  
  while [ $count -lt $max_tries ]; do
    if php check-db.php 2>/dev/null; then
      echo "✅ Database is ready!"
      return 0
    fi
    
    count=$((count + 1))
    echo "Database not ready yet (attempt $count/$max_tries)... waiting 2 seconds"
    sleep 2
  done
  
  echo "❌ Database connection failed after $max_tries attempts"
  echo ""
  echo "TROUBLESHOOTING:"
  echo "1. Check if PostgreSQL service exists in Railway project"
  echo "2. Verify DATABASE_URL environment variable is set"
  echo "3. Check PostgreSQL service logs in Railway dashboard"
  echo "4. Ensure services are in the same Railway project"
  echo ""
  echo "DATABASE_URL value: ${DATABASE_URL:0:30}..." # Show first 30 chars only
  return 1
}

# Wait for database to be ready
if ! wait_for_db; then
  exit 1
fi

echo ""
echo "Running migrations..."
max_retry=3
retry_count=0

while [ $retry_count -lt $max_retry ]; do
  if php artisan migrate --force 2>&1; then
    echo "✅ Migrations completed successfully"
    break
  else
    retry_count=$((retry_count + 1))
    if [ $retry_count -lt $max_retry ]; then
      echo "Migration failed, retrying ($retry_count/$max_retry)..."
      sleep 3
    else
      echo "❌ Migrations failed after $max_retry attempts"
      exit 1
    fi
  fi
done

# Run seeders (only if database is empty)
echo ""
echo "Running seeders..."
php artisan db:seed --force || echo "⚠️ Seeding skipped (data may already exist)"

# Create admin user if it doesn't exist
echo ""
echo "Creating admin user..."
php create-admin.php 2>/dev/null || echo "ℹ️ Admin user already exists or will be created later"

# Cache configuration for better performance
echo ""
echo "Caching configuration..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo ""
echo "✅ Deployment completed successfully!"
echo "Starting web server on port ${PORT:-8000}..."
echo ""

# Start the web server
exec php artisan serve --host=0.0.0.0 --port=${PORT:-8000}
