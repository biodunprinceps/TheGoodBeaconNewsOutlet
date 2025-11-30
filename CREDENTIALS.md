# The Good Beacon News Outlet - Access Credentials

## 🚀 Application URLs

| Service                  | URL                         | Description                   |
| ------------------------ | --------------------------- | ----------------------------- |
| **Laravel Application**  | http://localhost:8000       | Main application frontend     |
| **Filament Admin Panel** | http://localhost:8000/admin | CMS admin dashboard           |
| **Adminer Database GUI** | http://localhost:8080       | Database management interface |

---

## 🔑 Login Credentials

### Filament Admin Panel

Access the CMS admin dashboard at: **http://localhost:8000/admin**

```
Email:    admin@goodbeacon.com
Password: admin123
```

### Adminer (Database Management)

Access the database GUI at: http://localhost:8080

```
System:   PostgreSQL
Server:   db
Username: postgres
Password: secret
Database: good_beacon_cms
```

---

## 🐳 Docker Services

All services are managed via Docker Compose:

```bash
# Start all services
docker-compose up -d

# Stop all services
docker-compose down

# View logs
docker-compose logs -f

# Restart a specific service
docker-compose restart app
```

### Running Containers

| Container             | Service       | Port | Purpose          |
| --------------------- | ------------- | ---- | ---------------- |
| `good_beacon_app`     | Laravel/PHP   | 8000 | Main application |
| `good_beacon_db`      | PostgreSQL 15 | 5432 | Database         |
| `good_beacon_redis`   | Redis 7       | 6379 | Cache & Queue    |
| `good_beacon_adminer` | Adminer       | 8080 | Database GUI     |
| `good_beacon_node`    | Node.js 20    | 5173 | Frontend assets  |

---

## 📊 Database Information

### Connection Details

```
Driver:   pgsql
Host:     db (or localhost from host machine)
Port:     5432
Database: good_beacon_cms
Username: postgres
Password: secret
```

### Database Tables Created

-   ✅ `users` - User authentication
-   ✅ `permissions` - Spatie Permission tables
-   ✅ `roles` - User roles
-   ✅ `media` - Spatie Media Library
-   ✅ `categories` - Article categories (with nested support)
-   ✅ `articles` - Main content table
-   ✅ `tags` - Article tags
-   ✅ `article_tag` - Many-to-many pivot table
-   ✅ `cache` - Application cache
-   ✅ `jobs` - Queue jobs

---

## 🛠️ Common Commands

### Laravel Artisan

```bash
# Access container shell
docker-compose exec app bash

# Run migrations
docker-compose exec app php artisan migrate

# Create new admin user
docker-compose exec app php artisan make:filament-user

# Clear cache
docker-compose exec app php artisan cache:clear
docker-compose exec app php artisan config:clear
docker-compose exec app php artisan route:clear
docker-compose exec app php artisan view:clear

# Run database seeder
docker-compose exec app php artisan db:seed

# Generate application key
docker-compose exec app php artisan key:generate
```

### Composer

```bash
# Install PHP dependencies
docker-compose exec app composer install

# Update dependencies
docker-compose exec app composer update

# Require new package
docker-compose exec app composer require vendor/package
```

### NPM/Frontend

```bash
# Install dependencies
docker-compose exec app npm install

# Build for production
docker-compose exec app npm run build

# Development watch mode
docker-compose exec app npm run dev
```

### Database Operations

```bash
# Fresh migration (WARNING: Deletes all data)
docker-compose exec app php artisan migrate:fresh

# Migration with seeding
docker-compose exec app php artisan migrate:fresh --seed

# Rollback last migration
docker-compose exec app php artisan migrate:rollback

# Check migration status
docker-compose exec app php artisan migrate:status
```

---

## 📦 Installed Packages

### Backend (PHP/Composer)

-   **Laravel 12.10.1** - PHP Framework
-   **Filament 3.3.45** - Admin Panel
    -   filament/actions
    -   filament/forms
    -   filament/tables
    -   filament/notifications
    -   filament/widgets
    -   filament/infolists
-   **Spatie Packages**
    -   spatie/laravel-permission (6.23.0) - Roles & Permissions
    -   spatie/laravel-medialibrary (11.17.5) - Media Management
    -   spatie/laravel-sluggable (3.7.5) - SEO-friendly URLs
-   **Livewire 3.7.0** - Frontend framework
-   **Doctrine DBAL 4.4.0** - Database abstraction

### Frontend (NPM)

-   **Vite 7.2.4** - Build tool
-   **Tailwind CSS 3.x** - Utility-first CSS
-   **Alpine.js 3.x** - Minimal JavaScript framework

---

## 🔒 Security Notes

### IMPORTANT - Change Before Production!

1. **Application Key**: Already generated

    ```
    APP_KEY=base64:tmOZftncO+UnCUXA18z9DOeYkS21ZHxykP5o05Mh25I=
    ```

2. **Admin Password**: Change from default `admin123`

    ```bash
    docker-compose exec app php artisan tinker
    >>> $user = User::where('email', 'admin@goodbeacon.com')->first();
    >>> $user->password = Hash::make('new-secure-password');
    >>> $user->save();
    ```

3. **Database Password**: Update in both:

    - `.env` file: `DB_PASSWORD=secret`
    - `docker-compose.yml`: `POSTGRES_PASSWORD=secret`

4. **Environment**: Change `APP_ENV=production` and `APP_DEBUG=false` in production

---

## 🚀 Development Workflow

### Starting Work

```bash
# 1. Start all services
docker-compose up -d

# 2. Check if services are running
docker-compose ps

# 3. Start Laravel server (if not auto-started)
docker-compose exec app php artisan serve --host=0.0.0.0 --port=8000

# 4. Access admin panel
# Open browser: http://localhost:8000/admin
```

### Making Changes

```bash
# Edit code in your favorite editor (VS Code, etc.)
# Files are synced via Docker volumes

# After making migration changes
docker-compose exec app php artisan migrate

# After installing new packages
docker-compose exec app composer install
docker-compose exec app npm install

# Rebuild assets
docker-compose exec app npm run build
```

### Stopping Work

```bash
# Stop all services but keep data
docker-compose down

# Stop and remove all data (WARNING!)
docker-compose down -v
```

---

## 📝 Environment File (.env)

Key environment variables:

```env
APP_NAME="The Good Beacon News Outlet"
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost:8000

DB_CONNECTION=pgsql
DB_HOST=db
DB_PORT=5432
DB_DATABASE=good_beacon_cms
DB_USERNAME=postgres
DB_PASSWORD=secret

CACHE_STORE=redis
QUEUE_CONNECTION=redis
SESSION_DRIVER=redis

REDIS_HOST=good_beacon_redis
REDIS_PORT=6379
```

---

## 🆘 Troubleshooting

### Container Won't Start

```bash
# Check logs
docker-compose logs app

# Rebuild container
docker-compose down
docker-compose up -d --build
```

### Database Connection Error

```bash
# Verify PostgreSQL is running
docker-compose ps

# Check database credentials in .env
docker-compose exec app cat .env | grep DB_

# Test connection
docker-compose exec app php artisan tinker
>>> DB::connection()->getPdo();
```

### Permission Errors

```bash
# Fix file permissions
docker-compose exec app chown -R www-data:www-data /var/www
docker-compose exec app chmod -R 775 storage bootstrap/cache
```

### Clear All Caches

```bash
docker-compose exec app php artisan optimize:clear
```

---

## � Git & GitHub Deployment

### Initial Setup

```bash
# 1. Initialize git repository (if not already done)
git init

# 2. Add all files to staging
git add .

# 3. Create initial commit
git commit -m "Initial commit: Laravel CMS with Filament admin panel"

# 4. Create a new repository on GitHub
# Go to: https://github.com/new
# Repository name: TheGoodBeaconNewsOutlet
# Choose: Private or Public
# DO NOT initialize with README (we already have one)

# 5. Add GitHub remote (replace YOUR_USERNAME)
git remote add origin https://github.com/YOUR_USERNAME/TheGoodBeaconNewsOutlet.git

# 6. Push to GitHub
git branch -M main
git push -u origin main
```

### Regular Updates

```bash
# 1. Check status of changes
git status

# 2. Add specific files or all changes
git add .
# OR add specific files
git add app/Models/Article.php

# 3. Commit with descriptive message
git commit -m "Add feature: Rich text editor for articles"

# 4. Push to GitHub
git push origin main
```

### Best Practices

```bash
# Create a new branch for features
git checkout -b feature/article-comments
# Make changes, then commit
git add .
git commit -m "Add comments feature"
# Push the branch
git push origin feature/article-comments
# Create Pull Request on GitHub, then merge

# Pull latest changes before starting work
git pull origin main

# View commit history
git log --oneline

# Undo last commit (keep changes)
git reset --soft HEAD~1
```

### .gitignore Configuration

Your `.gitignore` should already exclude:

```
/vendor/
/node_modules/
.env
storage/*.key
/public/hot
/public/storage
/public/build
```

### Important: Never Commit

❌ `.env` file (contains secrets)
❌ `/vendor/` directory (installed via Composer)
❌ `/node_modules/` directory (installed via NPM)
❌ Database files
❌ Log files
✅ These are already in .gitignore

### Using SSH (Recommended for Security)

```bash
# 1. Generate SSH key (if you don't have one)
ssh-keygen -t ed25519 -C "your_email@example.com"

# 2. Copy SSH key to clipboard
cat ~/.ssh/id_ed25519.pub | pbcopy

# 3. Add to GitHub
# Go to: https://github.com/settings/keys
# Click "New SSH key"
# Paste the key and save

# 4. Change remote to SSH
git remote set-url origin git@github.com:biodunprinceps/TheGoodBeaconNewsOutlet.git

# 5. Now you can push without password
git push origin main
```

---

## �📚 Next Steps

1. ✅ **Create Filament Resources** - Generate admin panels for Articles, Categories, Tags DONE
2. ✅ **Configure Rich Text Editor** - Set up TinyMCE or similar for article content
3. ✅ **Set Up Media Library** - Configure image uploads and galleries
4. ✅ **Create Seeders** - Add sample data for testing
5. ✅ **Build Frontend** - Create public-facing article views
6. ✅ **Add SEO Features** - Meta tags, sitemaps, schema.org markup
7. ✅ **Configure Email** - Set up email notifications
8. ✅ **Add Search** - Implement full-text search with PostgreSQL

---

## 📖 Documentation Links

-   [Laravel Documentation](https://laravel.com/docs/12.x)
-   [Filament Documentation](https://filamentphp.com/docs)
-   [Spatie Permission](https://spatie.be/docs/laravel-permission)
-   [Spatie Media Library](https://spatie.be/docs/laravel-medialibrary)
-   [PostgreSQL Documentation](https://www.postgresql.org/docs/15/)
-   [Docker Compose Reference](https://docs.docker.com/compose/)

---

**Created:** 30 November 2025  
**Stack:** Laravel 12 + Filament 3 + PostgreSQL 15 + Redis 7  
**Status:** ✅ Development Ready
