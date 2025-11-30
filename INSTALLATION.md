# Installation Guide - The Good Beacon CMS

This guide will help you set up the Laravel CMS from scratch on your Mac.

## Step 1: Install Prerequisites

### Install Homebrew (if not installed)

```bash
/bin/bash -c "$(curl -fsSL https://raw.githubusercontent.com/Homebrew/install/HEAD/install.sh)"
```

### Install PHP 8.2

```bash
brew install php@8.2
brew link php@8.2 --force --overwrite

# Verify installation
php -v  # Should show PHP 8.2.x
```

### Install Composer

```bash
brew install composer

# Verify installation
composer -V  # Should show Composer version 2.x
```

### Install Node.js

```bash
brew install node@20
brew link node@20

# Verify installation
node -v  # Should show v20.x
npm -v   # Should show npm version
```

### Install PostgreSQL

```bash
brew install postgresql@15
brew services start postgresql@15

# Verify installation
psql --version  # Should show PostgreSQL 15.x
```

## Step 2: Create Laravel Project

```bash
# Navigate to your project directory
cd /Users/princeps/TheGoodBeaconNewsOutlet

# Create new Laravel project
composer create-project laravel/laravel .

# This will install Laravel 11 with all dependencies
```

## Step 3: Install Required Packages

```bash
# Install Filament (Admin Panel)
composer require filament/filament:"^3.2"

# Install Livewire 3 (comes with Filament)
# Already included with Filament

# Install Spatie packages (Permissions & Media Library)
composer require spatie/laravel-permission
composer require spatie/laravel-medialibrary

# Install additional packages
composer require spatie/laravel-sluggable
composer require spatie/laravel-sitemap
composer require dedoc/scramble  # API Documentation

# Development packages
composer require --dev laravel/pint
composer require --dev pestphp/pest
composer require --dev pestphp/pest-plugin-laravel
```

## Step 4: Configure Filament

```bash
# Install Filament
php artisan filament:install --panels

# Publish Filament config
php artisan vendor:publish --tag=filament-config
```

## Step 5: Setup Database

```bash
# Create database
createdb good_beacon_cms

# Update .env file with database credentials
# DB_CONNECTION=pgsql
# DB_DATABASE=good_beacon_cms
# DB_USERNAME=postgres
# DB_PASSWORD=

# Run migrations
php artisan migrate

# Create admin user
php artisan make:filament-user
```

## Step 6: Install Frontend Dependencies

```bash
# Install npm packages
npm install

# Install Tailwind CSS dependencies (should be included)
npm install -D tailwindcss postcss autoprefixer

# Build assets for development
npm run dev
```

## Step 7: Run the Application

```bash
# Start development server
php artisan serve

# In another terminal, run Vite dev server
npm run dev
```

Visit:

- Frontend: http://localhost:8000
- Admin: http://localhost:8000/admin

## Quick Commands Reference

```bash
# Clear all cache
php artisan optimize:clear

# Run migrations
php artisan migrate

# Fresh migration with seeding
php artisan migrate:fresh --seed

# Create Filament resource
php artisan make:filament-resource Article --generate

# Create Livewire component
php artisan make:livewire ArticleCard

# Run tests
php artisan test

# Code formatting
./vendor/bin/pint
```

## Troubleshooting

### "composer: command not found"

```bash
brew install composer
```

### "php: command not found"

```bash
brew install php@8.2
brew link php@8.2 --force
```

### Database connection error

```bash
# Make sure PostgreSQL is running
brew services start postgresql@15

# Check if database exists
psql -l
```

### Permission errors

```bash
chmod -R 775 storage bootstrap/cache
```

## Next Steps

After installation:

1. Configure your `.env` file
2. Run migrations: `php artisan migrate`
3. Seed database: `php artisan db:seed`
4. Create admin user: `php artisan make:filament-user`
5. Start building!

## Need Help?

Check:

- Laravel Documentation: https://laravel.com/docs/11.x
- Filament Documentation: https://filamentphp.com/docs/3.x
- Livewire Documentation: https://livewire.laravel.com/docs/3.x
