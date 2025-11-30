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
2. ✅ **Configure Rich Text Editor** - Enhanced Filament RichEditor with file attachments, headings, code blocks, and formatting
3. ✅ **Set Up Media Library** - Configure image uploads and galleries
4. ✅ **Create Seeders** - Add sample data for testing
5. ✅ **Build Frontend** - Create public-facing article views
6. ✅ **Add SEO Features** - Meta tags, sitemaps, schema.org markup
7. ✅ **Configure Email** - Set up email notifications
8. ✅ **Add Search** - Implement full-text search with PostgreSQL

---

## ☁️ Deployment to Production

### 🆓 100% FREE Deployment Options (Like Vercel)

#### Option 1: Render.com (100% Free Forever - RECOMMENDED)

**What's Free:**

-   ✅ Web Service (512MB RAM)
-   ✅ PostgreSQL Database (expires every 90 days, create new one)
-   ✅ Redis Instance (25MB)
-   ✅ SSL Certificates
-   ✅ Custom Domains
-   ✅ Auto-deploy from GitHub

**Trade-offs:**

-   ⚠️ App sleeps after 15min inactivity (wakes in ~30 seconds)
-   ⚠️ PostgreSQL expires every 90 days (just create a new one)
-   ✅ Perfect for testing/development

**Quick Deploy:**

1. Go to https://render.com
2. Sign up with GitHub
3. "New" → "Web Service" → Connect your repo
4. Runtime: **Docker**
5. Create PostgreSQL (free tier)
6. Create Redis (free tier)
7. Add environment variables
8. Click "Deploy"

**Start Command:**

```bash
php artisan migrate --force && php artisan serve --host=0.0.0.0 --port=$PORT
```

---

#### Option 2: Railway.app ($5/month Free Credit)

**What's Free:**

-   ✅ $5 monthly credit (usually enough!)
-   ✅ PostgreSQL, Redis included
-   ✅ SSL, Custom domains
-   ✅ No sleep/downtime
-   ✅ Better performance than Render free tier

**Quick Deploy:**

```bash
brew install railway
railway login
railway init
railway up
```

**Cost:** FREE if you stay under $5/month

---

#### Option 3: Fly.io (Free Tier)

**What's Free:**

-   ✅ 3 shared CPUs
-   ✅ 256MB RAM
-   ✅ 1GB PostgreSQL
-   ✅ SSL certificates

**Quick Deploy:**

```bash
curl -L https://fly.io/install.sh | sh
fly auth login
fly launch
fly deploy
```

---

### 💰 Cost Comparison vs Vercel

| Platform    | Free Tier  | Sleeps?        | Database      | Like Vercel?    |
| ----------- | ---------- | -------------- | ------------- | --------------- |
| **Render**  | ✅ Forever | ⚠️ Yes (15min) | ✅ Free       | ✅ Most similar |
| **Railway** | $5 credit  | ❌ No          | ✅ Included   | ⚠️ Better value |
| **Fly.io**  | ✅ Forever | ❌ No          | ✅ Free (1GB) | ⚠️ Technical    |
| **Vercel**  | ✅ Forever | ❌ No          | ❌ None       | ❌ No Laravel   |

---

### Recommended Platforms for Laravel + Docker

#### Option 1: Railway.app (Easiest - Recommended)

**Why Railway:**

-   ✅ Native Docker support
-   ✅ PostgreSQL & Redis included
-   ✅ $5/month free credit
-   ✅ Auto-deploy from GitHub
-   ✅ Custom domains with SSL

**Deploy Steps:**

```bash
# 1. Install Railway CLI
brew install railway

# 2. Login to Railway
railway login

# 3. Create new project
railway init

# 4. Add PostgreSQL
railway add --database postgres

# 5. Add Redis
railway add --database redis

# 6. Set environment variables
railway variables set APP_ENV=production
railway variables set APP_DEBUG=false
railway variables set APP_KEY=base64:tmOZftncO+UnCUXA18z9DOeYkS21ZHxykP5o05Mh25I=

# 7. Deploy
railway up

# 8. Run migrations
railway run php artisan migrate --force
railway run php artisan db:seed
```

**Or Deploy via Dashboard:**

1. Go to https://railway.app
2. "New Project" → "Deploy from GitHub"
3. Select `TheGoodBeaconNewsOutlet` repository
4. Add PostgreSQL & Redis services
5. Configure environment variables
6. Deploy automatically

---

#### Option 2: Render.com (Best Free Tier)

**Why Render:**

-   ✅ Free PostgreSQL database
-   ✅ Free Redis instance
-   ✅ Auto SSL certificates
-   ✅ Zero-downtime deploys

**Deploy Steps:**

1. Go to https://render.com
2. "New" → "Web Service"
3. Connect GitHub repository
4. Select "Docker" as runtime
5. Add environment variables:
    ```
    APP_ENV=production
    APP_DEBUG=false
    APP_KEY=base64:tmOZftncO+UnCUXA18z9DOeYkS21ZHxykP5o05Mh25I=
    ```
6. Create PostgreSQL database (free tier)
7. Create Redis instance (free tier)
8. Deploy

**Build Command:**

```bash
docker build -t good-beacon-cms .
```

**Start Command:**

```bash
php artisan serve --host=0.0.0.0 --port=$PORT
```

---

#### Option 3: DigitalOcean App Platform (Most Reliable)

**Why DigitalOcean:**

-   ✅ Proven reliability
-   ✅ Managed databases
-   ✅ $5/month starter plan
-   ✅ Great documentation

**Deploy Steps:**

1. Go to https://cloud.digitalocean.com/apps
2. "Create App" → "GitHub"
3. Select repository
4. Choose "Dockerfile" as source
5. Add Managed PostgreSQL ($15/month or dev database $7/month)
6. Add Managed Redis ($15/month or dev instance $7/month)
7. Set environment variables
8. Deploy

---

#### Option 4: Fly.io (Global Edge Deployment)

**Why Fly.io:**

-   ✅ Deploy globally
-   ✅ Fast performance
-   ✅ Docker-native
-   ✅ Free tier

**Deploy Steps:**

```bash
# 1. Install Fly CLI
curl -L https://fly.io/install.sh | sh

# 2. Login
fly auth login

# 3. Launch app
fly launch

# 4. Add PostgreSQL
fly postgres create

# 5. Add Redis
fly redis create

# 6. Deploy
fly deploy
```

---

### Pre-Deployment Checklist

Before deploying to production:

```bash
# 1. Update .env for production
APP_ENV=production
APP_DEBUG=false
APP_URL=https://your-domain.com

# 2. Generate new application key (for production)
docker-compose exec app php artisan key:generate

# 3. Optimize Laravel
docker-compose exec app php artisan config:cache
docker-compose exec app php artisan route:cache
docker-compose exec app php artisan view:cache

# 4. Build production assets
docker-compose exec app npm run build

# 5. Set proper permissions
docker-compose exec app chmod -R 775 storage bootstrap/cache

# 6. Test database connection
docker-compose exec app php artisan migrate:status
```

---

### Environment Variables for Production

Required environment variables:

```env
APP_NAME="The Good Beacon News Outlet"
APP_ENV=production
APP_KEY=base64:YOUR_PRODUCTION_KEY_HERE
APP_DEBUG=false
APP_URL=https://your-domain.com

DB_CONNECTION=pgsql
DB_HOST=your-db-host
DB_PORT=5432
DB_DATABASE=production_db_name
DB_USERNAME=production_user
DB_PASSWORD=secure_password_here

REDIS_HOST=your-redis-host
REDIS_PASSWORD=redis_password
REDIS_PORT=6379

CACHE_STORE=redis
QUEUE_CONNECTION=redis
SESSION_DRIVER=redis

MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=your_username
MAIL_PASSWORD=your_password
```

---

### Custom Domain Setup

After deployment, add your custom domain:

**Railway:**

```bash
railway domain add yourdomain.com
```

**Render:**

1. Go to Settings → Custom Domains
2. Add your domain
3. Update DNS records

**DigitalOcean:**

1. Go to Settings → Domains
2. Add domain
3. Configure DNS

**DNS Records:**

```
Type  Name  Value
A     @     Your-App-IP
CNAME www   your-app.platform.app
```

---

### Cost Comparison (Monthly)

| Platform     | Free Tier | Starter Plan | Includes         |
| ------------ | --------- | ------------ | ---------------- |
| Railway      | $5 credit | $10/month    | All services     |
| Render       | ✅ Yes    | $7/month     | Web + DB + Redis |
| DigitalOcean | ❌ No     | $12/month    | App + DB + Redis |
| Fly.io       | ✅ Yes    | $5/month     | App + small DB   |

**Recommended:** Start with **Railway** or **Render** free tier, upgrade as needed.

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
