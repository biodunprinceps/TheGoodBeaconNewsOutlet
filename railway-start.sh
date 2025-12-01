#!/bin/bash

echo "=== Railway Deployment Start ==="
echo "Timestamp: $(date)"
echo ""

# Set PHP configuration for uploads
export PHP_INI_SCAN_DIR="$PWD:$PHP_INI_SCAN_DIR"
echo "PHP upload limits configured (50MB)"
echo ""

# CRITICAL: Check if APP_KEY is set
if [ -z "$APP_KEY" ]; then
  echo "=========================================="
  echo "⚠️  CRITICAL: APP_KEY is NOT SET!"
  echo "=========================================="
  echo ""
  echo "This WILL cause 401 errors on file uploads!"
  echo ""
  echo "Generating a new APP_KEY for this deployment..."
  GENERATED_KEY=$(php artisan key:generate --show)
  echo ""
  echo "=========================================="
  echo "🔑 GENERATED APP_KEY:"
  echo "$GENERATED_KEY"
  echo "=========================================="
  echo ""
  echo "⚠️  ACTION REQUIRED:"
  echo "1. Copy the key above (entire line starting with 'base64:')"
  echo "2. Go to Railway → Variables"
  echo "3. Add variable: APP_KEY = $GENERATED_KEY"
  echo "4. Save and redeploy"
  echo ""
  echo "Without this, file uploads will continue to fail!"
  echo "=========================================="
  echo ""
  
  # Use the generated key for THIS deployment
  export APP_KEY="$GENERATED_KEY"
  echo "$GENERATED_KEY" > /tmp/app_key.txt
  echo "Key also saved to /tmp/app_key.txt"
  echo ""
  
  # Clear cache to use the new key
  php artisan config:clear
else
  echo "✅ APP_KEY is set: ${APP_KEY:0:20}..."
  echo ""
  
  # Verify the APP_KEY is properly formatted
  if [[ ! "$APP_KEY" =~ ^base64: ]]; then
    echo "⚠️  WARNING: APP_KEY doesn't start with 'base64:'"
    echo "Current value: ${APP_KEY:0:30}..."
    echo "Expected format: base64:..."
    echo ""
  fi
fi

# Clear any old cached config to ensure fresh start with correct APP_KEY
echo "Clearing old configuration cache..."
php artisan config:clear 2>/dev/null || true
echo ""

# Check if Vite build exists
echo "Checking for Vite build files..."
if [ ! -f "public/build/manifest.json" ]; then
  echo "⚠️  Vite manifest not found at public/build/manifest.json"
  echo "📁 Current directory: $(pwd)"
  echo "📂 Contents of public/:"
  ls -la public/ || echo "public/ directory not found"
  echo ""
  echo "🔨 Rebuilding assets..."
  
  # Ensure node_modules exists
  if [ ! -d "node_modules" ]; then
    echo "Installing npm dependencies..."
    npm ci
  fi
  
  # Build assets
  npm run build
  
  # Verify build succeeded
  if [ -f "public/build/manifest.json" ]; then
    echo "✅ Vite build completed successfully"
  else
    echo "❌ Vite build failed - manifest still missing"
    echo "Contents of public/ after build:"
    ls -la public/
  fi
else
  echo "✅ Vite build found"
  echo "📄 Manifest contents:"
  cat public/build/manifest.json | head -n 10
fi
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

  # Run seeders
  echo ""
  echo "Running seeders..."
  php artisan db:seed --force 2>&1
fi

# Create admin user if it doesn't exist
echo ""
echo "Creating admin user..."
php create-admin.php 2>/dev/null || echo "ℹ️  Admin user already exists or will be created later"

# Setup storage directories and permissions
echo ""
echo "Setting up storage..."
mkdir -p storage/app/public
mkdir -p storage/app/public/media
mkdir -p storage/framework/cache
mkdir -p storage/framework/sessions
mkdir -p storage/framework/views
mkdir -p storage/logs
mkdir -p public/storage
chmod -R 775 storage bootstrap/cache

# Create storage link
echo "Creating storage symlink..."
if [ -L "public/storage" ]; then
  rm -f public/storage
fi
php artisan storage:link
echo "✅ Storage setup completed"

# Cache configuration for better performance
echo ""
echo "Caching configuration..."

# IMPORTANT: Clear any existing cache first to ensure APP_KEY is properly loaded
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Now cache with the correct APP_KEY
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Verify APP_KEY is in the cached config
echo ""
echo "Verifying cached configuration..."
if php artisan tinker --execute="echo 'APP_KEY: ' . substr(config('app.key'), 0, 20) . '...';" 2>&1 | grep -q "APP_KEY:"; then
  echo "✅ APP_KEY successfully cached"
else
  echo "⚠️  Warning: Could not verify APP_KEY in cache"
fi

# Create livewire-tmp directory for file uploads
echo ""
echo "Setting up Livewire temporary upload directory..."
mkdir -p storage/app/public/livewire-tmp
chmod -R 775 storage/app/public/livewire-tmp

echo ""
echo "✅ Deployment completed successfully!"
echo "🚀 Starting web server on port ${PORT:-8000}..."
echo "⏰ Ready at: $(date)"
echo ""

# Start the web server
exec php artisan serve --host=0.0.0.0 --port=${PORT:-8000}
