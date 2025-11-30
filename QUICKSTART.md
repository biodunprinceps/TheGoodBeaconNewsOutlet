# Quick Start Guide

## 🚀 Automated Setup (Recommended)

Simply run the setup script:

```bash
./setup.sh
```

This will:

- ✅ Install Homebrew (if needed)
- ✅ Install PHP 8.2
- ✅ Install Composer
- ✅ Install Node.js 20
- ✅ Install PostgreSQL 15
- ✅ Create Laravel 11 project
- ✅ Install Filament 3.2
- ✅ Install all dependencies
- ✅ Configure database
- ✅ Set up environment

After the script completes:

```bash
# Run database migrations
php artisan migrate

# Create your admin user
php artisan make:filament-user

# Start the server
php artisan serve

# In another terminal, start Vite
npm run dev
```

Then visit:

- **Frontend**: http://localhost:8000
- **Admin Panel**: http://localhost:8000/admin

---

## 📖 Manual Setup

If you prefer manual installation, see [INSTALLATION.md](INSTALLATION.md)

---

## 🎯 What You Get

### Admin Panel Features

- ✨ Beautiful Filament 3.2 admin interface
- 📝 Rich text editor for articles
- 🖼️ Media library with image optimization
- 👥 User & role management
- 📊 Dashboard with analytics
- 🔍 Advanced search & filters

### Frontend Features

- ⚡ Livewire 3 for reactive components
- 🎨 Tailwind CSS for styling
- 📱 Fully responsive design
- 🔖 Categories & tags
- 🔎 Search functionality
- 📰 RSS feeds

### Technical Stack

- **Backend**: Laravel 11
- **Admin**: Filament 3.2
- **Frontend**: Livewire 3 + Alpine.js
- **CSS**: Tailwind CSS 3
- **Database**: PostgreSQL 15
- **Assets**: Vite 5

---

## 📝 Next Steps After Installation

1. **Create Database Structure**

   ```bash
   php artisan migrate
   ```

2. **Seed Sample Data** (Optional)

   ```bash
   php artisan db:seed
   ```

3. **Create Admin Account**

   ```bash
   php artisan make:filament-user
   ```

4. **Generate Filament Resources**

   ```bash
   php artisan make:filament-resource Article --generate --view
   php artisan make:filament-resource Category --generate
   php artisan make:filament-resource Tag --generate
   ```

5. **Start Building!**
   - Create articles in the admin panel
   - Customize frontend views
   - Add your branding

---

## 🛠️ Useful Commands

```bash
# Clear all cache
php artisan optimize:clear

# Run migrations
php artisan migrate

# Rollback last migration
php artisan migrate:rollback

# Fresh install (⚠️ destroys all data)
php artisan migrate:fresh --seed

# Run tests
php artisan test

# Format code
./vendor/bin/pint

# Create Livewire component
php artisan make:livewire ArticleList
```

---

## 📚 Documentation

- [Full README](README.md) - Complete feature list and architecture
- [Installation Guide](INSTALLATION.md) - Step-by-step manual setup
- [Laravel Docs](https://laravel.com/docs/11.x)
- [Filament Docs](https://filamentphp.com/docs/3.x)
- [Livewire Docs](https://livewire.laravel.com/docs/3.x)

---

## 🆘 Troubleshooting

### Setup script fails?

Run commands manually from [INSTALLATION.md](INSTALLATION.md)

### Database connection error?

Check PostgreSQL is running: `brew services list`

### Permission errors?

```bash
chmod -R 775 storage bootstrap/cache
```

### Vite not working?

```bash
npm install
npm run dev
```

---

**Ready to build something amazing! 🚀**
