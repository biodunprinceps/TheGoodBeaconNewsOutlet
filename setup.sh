#!/bin/bash

# The Good Beacon CMS - Automated Setup Script
# This script will install all prerequisites and set up the Laravel CMS

set -e  # Exit on error

echo "🚀 Starting The Good Beacon CMS Setup..."
echo "=========================================="

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

# Check if Homebrew is installed
if ! command -v brew &> /dev/null; then
    echo -e "${YELLOW}📦 Homebrew not found. Installing Homebrew...${NC}"
    /bin/bash -c "$(curl -fsSL https://raw.githubusercontent.com/Homebrew/install/HEAD/install.sh)"
else
    echo -e "${GREEN}✓ Homebrew is installed${NC}"
fi

# Install PHP 8.2
if ! command -v php &> /dev/null; then
    echo -e "${YELLOW}🐘 Installing PHP 8.2...${NC}"
    brew install php@8.2
    brew link php@8.2 --force --overwrite
else
    echo -e "${GREEN}✓ PHP is installed ($(php -v | head -n 1))${NC}"
fi

# Install Composer
if ! command -v composer &> /dev/null; then
    echo -e "${YELLOW}🎼 Installing Composer...${NC}"
    brew install composer
else
    echo -e "${GREEN}✓ Composer is installed ($(composer -V))${NC}"
fi

# Install Node.js
if ! command -v node &> /dev/null; then
    echo -e "${YELLOW}📗 Installing Node.js...${NC}"
    brew install node@20
    brew link node@20
else
    echo -e "${GREEN}✓ Node.js is installed ($(node -v))${NC}"
fi

# Install PostgreSQL
if ! command -v psql &> /dev/null; then
    echo -e "${YELLOW}🐘 Installing PostgreSQL...${NC}"
    brew install postgresql@15
    brew services start postgresql@15
    sleep 3  # Wait for PostgreSQL to start
else
    echo -e "${GREEN}✓ PostgreSQL is installed${NC}"
fi

echo ""
echo "=========================================="
echo "🏗️  Creating Laravel Project..."
echo "=========================================="

# Check if Laravel is already installed
if [ -f "composer.json" ]; then
    echo -e "${YELLOW}⚠️  Laravel project already exists. Skipping creation...${NC}"
else
    # Create Laravel project
    composer create-project laravel/laravel . --prefer-dist
fi

echo ""
echo "=========================================="
echo "📦 Installing PHP Dependencies..."
echo "=========================================="

# Install Filament and other packages
composer require filament/filament:"^3.2" -W
composer require spatie/laravel-permission
composer require spatie/laravel-medialibrary
composer require spatie/laravel-sluggable
composer require spatie/laravel-sitemap
composer require dedoc/scramble

# Dev dependencies
composer require --dev laravel/pint
composer require --dev pestphp/pest
composer require --dev pestphp/pest-plugin-laravel

echo ""
echo "=========================================="
echo "📦 Installing Frontend Dependencies..."
echo "=========================================="

npm install

echo ""
echo "=========================================="
echo "⚙️  Configuring Application..."
echo "=========================================="

# Create .env if it doesn't exist
if [ ! -f ".env" ]; then
    cp .env.example .env
    php artisan key:generate
fi

# Install Filament
php artisan filament:install --panels

# Create database
echo -e "${YELLOW}📊 Creating database 'good_beacon_cms'...${NC}"
createdb good_beacon_cms 2>/dev/null || echo -e "${YELLOW}Database might already exist${NC}"

# Update .env for PostgreSQL
sed -i '' 's/DB_CONNECTION=sqlite/DB_CONNECTION=pgsql/' .env
sed -i '' 's/# DB_HOST=127.0.0.1/DB_HOST=127.0.0.1/' .env
sed -i '' 's/# DB_PORT=3306/DB_PORT=5432/' .env
sed -i '' 's/# DB_DATABASE=laravel/DB_DATABASE=good_beacon_cms/' .env
sed -i '' 's/# DB_USERNAME=root/DB_USERNAME=postgres/' .env
sed -i '' 's/# DB_PASSWORD=/DB_PASSWORD=/' .env

# Create storage link
php artisan storage:link

echo ""
echo "=========================================="
echo -e "${GREEN}✅ Setup Complete!${NC}"
echo "=========================================="
echo ""
echo "Next steps:"
echo "1. Run migrations:"
echo "   php artisan migrate"
echo ""
echo "2. Create admin user:"
echo "   php artisan make:filament-user"
echo ""
echo "3. Start development server:"
echo "   php artisan serve"
echo ""
echo "4. In another terminal, start Vite:"
echo "   npm run dev"
echo ""
echo "5. Visit your application:"
echo "   Frontend: http://localhost:8000"
echo "   Admin: http://localhost:8000/admin"
echo ""
echo -e "${GREEN}🎉 Happy coding!${NC}"
