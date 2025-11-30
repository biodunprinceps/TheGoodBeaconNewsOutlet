# Deployment Configuration

## 🚀 Automatic Seeding on Deployment

Your CMS is configured to automatically seed the database with sample content when deployed to production.

---

## 📋 What Gets Seeded

When you deploy to Railway (or any production environment), the following data is automatically created:

### 1. **Categories (8)**

-   World News
-   Politics
-   Technology
-   Business
-   Science
-   Health
-   Sports
-   Entertainment

### 2. **Tags (20)**

Including: Breaking, Exclusive, Analysis, Interview, Investigation, etc.

### 3. **Articles (10)**

Realistic news articles with:

-   ✅ Featured images
-   ✅ Full content (rich text)
-   ✅ SEO meta data
-   ✅ Published status
-   ✅ Category assignments
-   ✅ Tag assignments
-   ✅ Author information
-   ✅ View counts

---

## 🔧 How It Works

### Railway Deployment Process

When deploying to Railway, the `railway-start.sh` script runs:

```bash
# 1. Run database migrations
php artisan migrate --force

# 2. Seed database (only if empty)
php artisan db:seed --force

# 3. Create admin user
php create-admin.php

# 4. Cache configuration
php artisan config:cache
php artisan route:cache
php artisan view:cache

# 5. Start server
php artisan serve --host=0.0.0.0 --port=$PORT
```

### Docker Deployment Process

When using Docker Compose, the `docker-entrypoint.sh` script runs:

```bash
# 1. Run migrations
php artisan migrate --force

# 2. Seed database
php artisan db:seed --force

# 3. Create admin user
php create-admin.php

# 4. Start server
php artisan serve --host=0.0.0.0 --port=8080
```

---

## 🛡️ Idempotent Seeding

The database seeder is **idempotent**, meaning it's safe to run multiple times:

-   ✅ **First deployment:** Creates all sample data
-   ✅ **Subsequent deployments:** Skips seeding if data already exists
-   ✅ **No duplicates:** Won't create duplicate categories, tags, or articles

### How It Checks

The `DatabaseSeeder` checks if articles exist:

```php
if (Article::count() === 0) {
    // Seed the database
} else {
    // Skip seeding
}
```

---

## 🎯 Admin User Creation

An admin user is automatically created with these credentials:

```
Email:    admin@goodbeacon.com
Password: admin123
```

**⚠️ IMPORTANT:** Change this password after first login!

```bash
# Change password via tinker
php artisan tinker
>>> $user = User::where('email', 'admin@goodbeacon.com')->first();
>>> $user->password = Hash::make('your-secure-password');
>>> $user->save();
```

---

## 🔄 Manual Seeding

If you need to manually seed the database:

### Seed Everything

```bash
php artisan db:seed --force
```

### Seed Specific Seeders

```bash
# Seed only categories
php artisan db:seed --class=CategorySeeder --force

# Seed only tags
php artisan db:seed --class=TagSeeder --force

# Seed only articles
php artisan db:seed --class=ArticleSeeder --force
```

### Fresh Database with Seeding

```bash
# ⚠️ WARNING: This will DELETE all data!
php artisan migrate:fresh --seed --force
```

---

## 📊 Sample Data Details

### Article Examples

1. **Breakthrough in Quantum Computing** (Technology)
2. **Climate Summit Reaches Historic Agreement** (World News)
3. **New Economic Policy** (Business)
4. **Medical Research Breakthrough** (Health)
5. **Political Analysis** (Politics)
6. **Sports Championship** (Sports)
7. **Entertainment Industry News** (Entertainment)
8. **Scientific Discovery** (Science)
9. **Technology Innovation** (Technology)
10. **Global Economic Trends** (Business)

### Features Included

Each article has:

-   ✅ Realistic title and content
-   ✅ SEO-optimized excerpts
-   ✅ Meta descriptions and keywords
-   ✅ Featured images (via Spatie Media Library)
-   ✅ Published status with publication date
-   ✅ View counts (random realistic numbers)
-   ✅ Category assignment
-   ✅ Multiple tag assignments
-   ✅ Author (admin user)

---

## 🌐 Environment-Specific Behavior

### Local Development (Docker)

-   Runs seeder on every container start
-   Uses PostgreSQL database
-   Data persists in Docker volume

### Railway Production

-   Runs seeder on first deployment
-   Skips seeding on subsequent deployments
-   Uses Railway PostgreSQL database
-   Data persists in production database

---

## 🔍 Verifying Seeded Data

After deployment, verify the data:

```bash
# Check article count
php artisan tinker
>>> Article::count();
=> 10

# Check categories
>>> Category::count();
=> 8

# Check tags
>>> Tag::count();
=> 20

# List all articles
>>> Article::select('id', 'title', 'status')->get();
```

Or visit:

-   Homepage: `https://your-app.railway.app/`
-   Admin Panel: `https://your-app.railway.app/admin`

---

## 🚨 Troubleshooting

### Seeder Not Running

**Check logs:**

```bash
# Railway
railway logs

# Docker
docker compose logs app
```

**Common issues:**

1. **Permission errors:**

    ```bash
    chmod +x railway-start.sh
    chmod +x docker-entrypoint.sh
    ```

2. **Database connection:**

    - Verify `DATABASE_URL` is set correctly
    - Check database credentials in `.env`

3. **Migration errors:**
    - Ensure all migrations run successfully first
    - Check: `php artisan migrate:status`

### Seeder Fails

**Run seeder manually:**

```bash
php artisan db:seed --force --verbose
```

**Check seeder logs:**

```bash
php artisan db:seed --class=ArticleSeeder --force --verbose
```

### Duplicate Data

If you accidentally created duplicates:

```bash
# Delete all articles (keeps migrations)
php artisan tinker
>>> Article::truncate();
>>> Category::truncate();
>>> Tag::truncate();

# Re-seed
php artisan db:seed --force
```

---

## 📝 Customizing Seeded Data

To customize the sample data, edit these files:

### Categories

`database/seeders/CategorySeeder.php`

```php
Category::create([
    'name' => 'Your Category',
    'slug' => 'your-category',
    'description' => 'Category description',
    // ...
]);
```

### Tags

`database/seeders/TagSeeder.php`

```php
Tag::create([
    'name' => 'Your Tag',
    'slug' => 'your-tag',
]);
```

### Articles

`database/seeders/ArticleSeeder.php`

```php
Article::create([
    'title' => 'Your Article Title',
    'content' => 'Article content...',
    // ...
]);
```

After editing, commit and push:

```bash
git add database/seeders/
git commit -m "Update seeder data"
git push origin main
```

Railway will automatically re-deploy with the new seed data.

---

## ✅ Deployment Checklist

Before deploying to production:

-   [ ] Verify seeders work locally: `php artisan db:seed`
-   [ ] Check all migrations are committed
-   [ ] Update `.env` with production values
-   [ ] Set `APP_ENV=production`
-   [ ] Set `APP_DEBUG=false`
-   [ ] Generate new `APP_KEY` for production
-   [ ] Configure production database credentials
-   [ ] Set up production email provider
-   [ ] Test deployment on Railway staging first
-   [ ] Change admin password after first deployment

---

## 🎉 Benefits

With automatic seeding, your deployed CMS will:

✅ Have sample content immediately  
✅ Look professional and populated  
✅ Be ready for demonstrations  
✅ Have realistic data for testing  
✅ Include SEO-optimized articles  
✅ Show proper image handling  
✅ Display correct relationships  
✅ Be ready for content replacement

---

**Last Updated:** 30 November 2025  
**Status:** ✅ Configured for Railway & Docker
