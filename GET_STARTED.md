# 🚀 Getting Started - Choose Your Path

You have **two options** to set up The Good Beacon CMS:

---

## Option 1: Docker (Recommended - No PHP installation needed!)

**Best if**: You don't want to install PHP/Composer locally, or want an isolated environment.

### Prerequisites

- Docker Desktop for Mac ([Download](https://www.docker.com/products/docker-desktop))

### Setup Steps

1. **Install Docker Desktop** and make sure it's running

2. **Clone/Navigate to project**

   ```bash
   cd /Users/princeps/TheGoodBeaconNewsOutlet
   ```

3. **Start Docker containers**

   ```bash
   docker-compose up -d
   ```

4. **Install dependencies**

   ```bash
   docker-compose exec app composer install
   docker-compose exec app cp .env.example .env
   docker-compose exec app php artisan key:generate
   ```

5. **Run migrations**

   ```bash
   docker-compose exec app php artisan migrate
   ```

6. **Create admin user**

   ```bash
   docker-compose exec app php artisan make:filament-user
   ```

7. **Access your site**
   - Frontend: http://localhost:8000
   - Admin: http://localhost:8000/admin

### Docker Commands

```bash
# Start containers
docker-compose up -d

# Stop containers
docker-compose down

# View logs
docker-compose logs -f app

# Run artisan commands
docker-compose exec app php artisan [command]

# Access app shell
docker-compose exec app sh
```

---

## Option 2: Native Installation (Direct on macOS)

**Best if**: You want PHP/Composer installed globally on your Mac.

### Step 1: Install Homebrew

```bash
/bin/bash -c "$(curl -fsSL https://raw.githubusercontent.com/Homebrew/install/HEAD/install.sh)"
```

After installation, follow the instructions to add Homebrew to your PATH.

### Step 2: Install Prerequisites

```bash
# Install PHP 8.2
brew install php@8.2

# Install Composer
brew install composer

# Install Node.js
brew install node@20

# Install PostgreSQL
brew install postgresql@15
brew services start postgresql@15
```

### Step 3: Verify Installation

```bash
php -v        # Should show PHP 8.2.x
composer -V   # Should show Composer 2.x
node -v       # Should show v20.x
psql --version # Should show PostgreSQL 15.x
```

### Step 4: Create Laravel Project

```bash
cd /Users/princeps/TheGoodBeaconNewsOutlet

# Create Laravel project
composer create-project laravel/laravel .

# Install Filament
composer require filament/filament:"^3.2" -W

# Install additional packages
composer require spatie/laravel-permission
composer require spatie/laravel-medialibrary
composer require spatie/laravel-sluggable

# Install dev packages
composer require --dev laravel/pint pestphp/pest
```

### Step 5: Configure

```bash
# Setup environment
cp .env.example .env
php artisan key:generate

# Install Filament
php artisan filament:install --panels

# Create database
createdb good_beacon_cms

# Update .env database settings
# DB_CONNECTION=pgsql
# DB_DATABASE=good_beacon_cms
# DB_USERNAME=postgres
# DB_PASSWORD=

# Run migrations
php artisan migrate

# Create storage link
php artisan storage:link

# Install frontend dependencies
npm install
```

### Step 6: Create Admin User

```bash
php artisan make:filament-user
```

### Step 7: Start Development Servers

```bash
# Terminal 1 - Laravel
php artisan serve

# Terminal 2 - Vite (assets)
npm run dev
```

### Step 8: Access Your Site

- Frontend: http://localhost:8000
- Admin: http://localhost:8000/admin

---

## What's Next?

After setup is complete:

1. **Create your first article** in the admin panel
2. **Customize the design** in `resources/views`
3. **Add your branding** and colors
4. **Configure features** as needed

---

## Quick Reference

### Artisan Commands

```bash
# Create Filament resource
php artisan make:filament-resource Article --generate --view

# Create Livewire component
php artisan make:livewire ArticleCard

# Clear cache
php artisan optimize:clear

# Run tests
php artisan test

# Format code
./vendor/bin/pint
```

### NPM Commands

```bash
# Development
npm run dev

# Production build
npm run build

# Watch for changes
npm run dev
```

---

## Troubleshooting

### Docker Issues

- Make sure Docker Desktop is running
- Try: `docker-compose down && docker-compose up -d --build`

### Native Installation Issues

**Command not found?**

```bash
# Add to PATH in ~/.zshrc
export PATH="/opt/homebrew/bin:$PATH"
export PATH="/opt/homebrew/opt/php@8.2/bin:$PATH"
```

**Database connection error?**

```bash
brew services restart postgresql@15
```

**Permission errors?**

```bash
chmod -R 775 storage bootstrap/cache
```

---

## Documentation

- 📖 [Full README](README.md) - Complete feature list
- 📝 [Installation Guide](INSTALLATION.md) - Detailed steps
- 🐳 [Docker Setup](DOCKER.md) - Docker-specific guide
- 🍎 [macOS Setup](SETUP_MACOS.md) - Native macOS guide

---

**Choose your path and let's build something amazing! 🚀**
