#!/bin/bash

echo "=== Railway Deployment Start ==="
echo "Timestamp: $(date)"
echo ""

# Function to wait for database
wait_for_db() {
  echo "Waiting for PostgreSQL database to be ready..."
  echo "Note: Railway PostgreSQL can take 1-3 minutes to fully start on first deployment"
  echo ""
  
  max_tries=90  # Increased to 3 minutes (90 * 2 seconds)
  count=0
  
  while [ $count -lt $max_tries ]; do
    if php check-db.php 2>/dev/null; then
      echo ""
      echo "✅ Database is ready!"
      return 0
    fi
    
    count=$((count + 1))
    
    # Show progress every 5 attempts (10 seconds)
    if [ $((count % 5)) -eq 0 ]; then
      elapsed=$((count * 2))
      echo "⏱️  Still waiting... ${elapsed}s elapsed (attempt $count/$max_tries)"
      echo "   Railway PostgreSQL container may still be starting up"
    else
      echo -n "."
    fi
    
    sleep 2
  done
  
  echo ""
  echo "❌ Database connection failed after $((max_tries * 2)) seconds"
  echo ""
  echo "TROUBLESHOOTING:"
  echo "1. Check Railway dashboard: Is PostgreSQL service 'Active'?"
  echo "2. PostgreSQL might still be starting - check container status"
  echo "3. Verify DATABASE_URL is set in environment variables"
  echo "4. Check both services are in the same Railway project"
  echo ""
  echo "DATABASE_URL: ${DATABASE_URL:0:30}..."
  return 1
}

# Wait for database to be ready
if ! wait_for_db; then
  echo ""
  echo "💡 TIP: If PostgreSQL is still starting, Railway will automatically"
  echo "    retry this deployment once the database is fully ready."
  exit 1
fi

echo ""
# Check if FRESH_SEED environment variable is set
if [ "$FRESH_SEED" = "true" ]; then
  echo "🔄 FRESH_SEED is enabled - dropping all tables and reseeding..."
  if php artisan migrate:fresh --seed --force 2>&1; then
    echo "✅ Database refreshed and seeded successfully"
  else
    echo "❌ Fresh migration failed"
    exit 1
  fi
else
  echo "Running migrations..."
  max_retry=5  # Increased retry attempts
  retry_count=0

  while [ $retry_count -lt $max_retry ]; do
    if php artisan migrate --force 2>&1; then
      echo "✅ Migrations completed successfully"
      break
    else
      retry_count=$((retry_count + 1))
      if [ $retry_count -lt $max_retry ]; then
        echo "⚠️  Migration failed, retrying in 5 seconds ($retry_count/$max_retry)..."
        sleep 5
      else
        echo "❌ Migrations failed after $max_retry attempts"
        exit 1
      fi
    fi
  done

  # Run seeders (only if database is empty)
  echo ""
  echo "Running seeders..."
  if php artisan db:seed --force 2>&1; then
    echo "✅ Seeding completed successfully"
  else
    echo "ℹ️  Seeding skipped (database already contains data)"
  fi
fi

# Create admin user if it doesn't exist
echo ""
echo "Creating admin user..."
php create-admin.php 2>/dev/null || echo "ℹ️  Admin user already exists or will be created later"

# Cache configuration for better performance
echo ""
echo "Caching configuration..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo ""
echo "✅ Deployment completed successfully!"
echo "🚀 Starting web server on port ${PORT:-8000}..."
echo "⏰ Ready at: $(date)"
echo ""

# Start the web server
exec php artisan serve --host=0.0.0.0 --port=${PORT:-8000}
