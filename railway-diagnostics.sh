#!/bin/bash

echo "╔═══════════════════════════════════════════════════════════════╗"
echo "║         RAILWAY DEPLOYMENT DIAGNOSTICS                        ║"
echo "╚═══════════════════════════════════════════════════════════════╝"
echo ""

echo "🔍 ENVIRONMENT VARIABLES CHECK:"
echo "================================"
echo ""

# Check if DATABASE_URL exists
if [ -z "$DATABASE_URL" ]; then
    echo "❌ DATABASE_URL is NOT set"
    echo "   This is the problem! PostgreSQL service might not be linked."
    echo ""
    echo "   FIX: In Railway Dashboard:"
    echo "   1. Go to your project"
    echo "   2. Click 'New' → 'Database' → 'Add PostgreSQL'"
    echo "   3. Railway will auto-set DATABASE_URL"
    echo ""
else
    echo "✅ DATABASE_URL is set"
    # Show first 50 chars only for security
    echo "   Value: ${DATABASE_URL:0:50}..."
    echo ""
    
    # Parse DATABASE_URL
    if [[ $DATABASE_URL =~ postgres://([^:]+):([^@]+)@([^:]+):([^/]+)/(.+) ]]; then
        DB_USER="${BASH_REMATCH[1]}"
        DB_HOST="${BASH_REMATCH[3]}"
        DB_PORT="${BASH_REMATCH[4]}"
        DB_NAME="${BASH_REMATCH[5]}"
        
        echo "   Parsed connection details:"
        echo "   - Host: $DB_HOST"
        echo "   - Port: $DB_PORT"
        echo "   - Database: $DB_NAME"
        echo "   - User: $DB_USER"
    fi
fi
echo ""

# Check other important variables
echo "📋 OTHER ENVIRONMENT VARIABLES:"
echo "================================"
echo ""

if [ -z "$PORT" ]; then
    echo "⚠️  PORT is not set (will default to 8000)"
else
    echo "✅ PORT = $PORT"
fi

if [ -z "$APP_KEY" ]; then
    echo "❌ APP_KEY is NOT set - Laravel won't work properly!"
else
    echo "✅ APP_KEY is set"
fi

if [ -z "$APP_ENV" ]; then
    echo "⚠️  APP_ENV is not set (will default to local)"
else
    echo "✅ APP_ENV = $APP_ENV"
fi
echo ""

# Network connectivity check
echo "🌐 NETWORK CONNECTIVITY TEST:"
echo "=============================="
echo ""

if [ ! -z "$DATABASE_URL" ] && [[ $DATABASE_URL =~ @([^:]+):([^/]+) ]]; then
    DB_HOST="${BASH_REMATCH[1]}"
    DB_PORT="${BASH_REMATCH[2]}"
    
    echo "Testing connection to PostgreSQL server..."
    echo "Host: $DB_HOST"
    echo "Port: $DB_PORT"
    echo ""
    
    # Try to connect using nc (netcat) if available
    if command -v nc &> /dev/null; then
        echo "Testing with netcat..."
        if timeout 5 nc -z "$DB_HOST" "$DB_PORT" 2>/dev/null; then
            echo "✅ PostgreSQL port is reachable!"
        else
            echo "❌ Cannot reach PostgreSQL port"
            echo "   This means network connectivity issue or PostgreSQL not started"
        fi
    else
        echo "ℹ️  netcat not available, skipping port test"
    fi
    echo ""
    
    # Try ping if available
    if command -v ping &> /dev/null; then
        echo "Testing DNS resolution..."
        if timeout 3 ping -c 1 "$DB_HOST" &> /dev/null; then
            echo "✅ Can resolve and ping database host"
        else
            echo "⚠️  Cannot ping database host (this is normal in some networks)"
        fi
    fi
fi
echo ""

# PHP database connection test
echo "🔌 PHP DATABASE CONNECTION TEST:"
echo "================================="
echo ""

if php check-db.php 2>&1; then
    echo "✅ PHP can connect to database!"
else
    echo "❌ PHP cannot connect to database"
    echo ""
    echo "   Common causes:"
    echo "   1. PostgreSQL service not started yet (wait and retry)"
    echo "   2. DATABASE_URL incorrectly formatted"
    echo "   3. PostgreSQL service in different Railway project"
    echo "   4. Network configuration issue"
fi
echo ""

echo "📝 RECOMMENDATIONS:"
echo "==================="
echo ""

if [ -z "$DATABASE_URL" ]; then
    echo "1. ❗ CRITICAL: Add PostgreSQL database to Railway project"
    echo "   - Go to Railway Dashboard"
    echo "   - Click 'New' → 'Database' → 'PostgreSQL'"
    echo ""
elif ! php check-db.php 2>/dev/null; then
    echo "1. ⏱️  Database exists but not ready - retry in a few seconds"
    echo "2. 🔍 Check PostgreSQL service logs in Railway dashboard"
    echo "3. 🔗 Verify both services are in same Railway project"
    echo ""
else
    echo "✅ Everything looks good! Database is accessible."
    echo ""
fi

echo "╔═══════════════════════════════════════════════════════════════╗"
echo "║         DIAGNOSTICS COMPLETE                                  ║"
echo "╚═══════════════════════════════════════════════════════════════╝"
