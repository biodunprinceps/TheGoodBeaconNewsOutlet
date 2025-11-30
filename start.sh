#!/bin/bash

# The Good Beacon CMS - Quick Start Helper

echo "🎯 The Good Beacon CMS - Quick Start"
echo "===================================="
echo ""
echo "Choose your installation method:"
echo ""
echo "1. Docker (Recommended - No PHP needed)"
echo "2. Native macOS (Requires PHP/Composer)"
echo "3. View Documentation"
echo "4. Exit"
echo ""
read -p "Enter your choice (1-4): " choice

case $choice in
  1)
    echo ""
    echo "🐳 Starting Docker installation..."
    echo ""
    
    if ! command -v docker &> /dev/null; then
        echo "❌ Docker is not installed!"
        echo ""
        echo "Please install Docker Desktop from:"
        echo "https://www.docker.com/products/docker-desktop"
        exit 1
    fi
    
    echo "✓ Docker found!"
    echo ""
    echo "Starting containers..."
    docker-compose up -d
    
    echo ""
    echo "Installing Laravel..."
    docker-compose exec app composer create-project laravel/laravel tmp
    docker-compose exec app sh -c "mv tmp/* tmp/.* . 2>/dev/null; rm -rf tmp"
    
    echo ""
    echo "Installing Filament and dependencies..."
    docker-compose exec app composer require filament/filament:"^3.2" -W
    docker-compose exec app composer require spatie/laravel-permission spatie/laravel-medialibrary spatie/laravel-sluggable
    
    echo ""
    echo "Setting up environment..."
    docker-compose exec app cp .env.example .env
    docker-compose exec app php artisan key:generate
    docker-compose exec app php artisan filament:install --panels
    
    echo ""
    echo "Running migrations..."
    docker-compose exec app php artisan migrate
    
    echo ""
    echo "✅ Installation complete!"
    echo ""
    echo "Create your admin user:"
    echo "  docker-compose exec app php artisan make:filament-user"
    echo ""
    echo "Then visit:"
    echo "  Frontend: http://localhost:8000"
    echo "  Admin: http://localhost:8000/admin"
    ;;
    
  2)
    echo ""
    echo "🍎 Starting native macOS installation..."
    echo ""
    
    # Check prerequisites
    if ! command -v php &> /dev/null; then
        echo "❌ PHP is not installed!"
        echo ""
        echo "Install with: brew install php@8.2"
        echo ""
        echo "Or run: ./setup.sh"
        exit 1
    fi
    
    if ! command -v composer &> /dev/null; then
        echo "❌ Composer is not installed!"
        echo ""
        echo "Install with: brew install composer"
        exit 1
    fi
    
    echo "✓ PHP found: $(php -v | head -n 1)"
    echo "✓ Composer found"
    echo ""
    echo "Running setup script..."
    ./setup.sh
    ;;
    
  3)
    echo ""
    echo "📚 Documentation Files:"
    echo ""
    echo "  GET_STARTED.md    - Start here! Choose your path"
    echo "  README.md         - Complete project overview"
    echo "  INSTALLATION.md   - Detailed installation guide"
    echo "  PROJECT_SUMMARY.md - What you're getting"
    echo "  QUICKSTART.md     - Quick reference"
    echo ""
    echo "Open any file with:"
    echo "  cat GET_STARTED.md"
    echo ""
    ;;
    
  4)
    echo "Goodbye! 👋"
    exit 0
    ;;
    
  *)
    echo "Invalid choice. Please run again and choose 1-4."
    exit 1
    ;;
esac
