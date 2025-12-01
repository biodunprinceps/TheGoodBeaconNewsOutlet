# 🚀 First Deployment Checklist

## ⚠️ CRITICAL: Complete These Steps Before Going Live!

This checklist ensures your application is secure and production-ready. **Do not skip any steps!**

---

## 🔒 Step 1: Security Configuration (5 minutes)

### 1.1 Generate New Application Key

**Why:** The APP_KEY is used for encryption. Never use the default!

```bash
# For Docker deployment:
docker-compose exec app php artisan key:generate

# For native Laravel:
php artisan key:generate
```

**Verify:** Your `.env` file should now have a new `APP_KEY=base64:...` value.

---

### 1.2 Change Default Admin Password

**Default credentials (INSECURE):**

-   Email: `admin@goodbeacon.com`
-   Password: `admin123`

**⚠️ CHANGE IMMEDIATELY:**

```bash
# Method 1: Via Tinker (Recommended)
docker-compose exec app php artisan tinker
>>> $user = User::where('email', 'admin@goodbeacon.com')->first();
>>> $user->password = Hash::make('YourSecurePassword123!@#');
>>> $user->save();
>>> exit

# Method 2: Create a new admin and delete the default
docker-compose exec app php artisan make:filament-user
# Then delete the old admin via the admin panel
```

**Best Practice:** Use a password with:

-   At least 12 characters
-   Mix of uppercase, lowercase, numbers, symbols
-   No dictionary words

---

### 1.3 Update Database Credentials

**Default database password:** `secret` (INSECURE!)

**For Docker users:**

1. Edit `docker-compose.yml`:

    ```yaml
    POSTGRES_PASSWORD: your_secure_db_password_here
    ```

2. Update `.env`:

    ```env
    DB_PASSWORD=your_secure_db_password_here
    ```

3. Recreate database container:
    ```bash
    docker-compose down -v
    docker-compose up -d
    docker-compose exec app php artisan migrate --seed
    ```

**For Railway/Render:** Database password is automatically generated (secure by default).

---

### 1.4 Verify Environment Variables

**Check your `.env` file has these settings:**

```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://your-actual-domain.com

# NEVER set these to true in production:
APP_DEBUG=false  # ✅ Correct
LOG_LEVEL=error  # ✅ Use 'error' or 'warning', not 'debug'
```

---

## 📦 Step 2: Storage Configuration (CRITICAL for Railway)

### 2.1 Configure Persistent Storage

**⚠️ Railway Warning:** Files uploaded to `/storage/app/public` will be deleted on every redeploy!

**Choose ONE solution:**

#### Option A: Railway Volumes (Quick Fix)

```bash
# In Railway Dashboard:
1. Go to your project
2. Click "Settings" → "Volumes"
3. Click "New Volume"
4. Mount point: /app/storage/app/public
5. Click "Add"
6. Redeploy
```

**Cost:** $0.25/GB/month (approximately $2-5/month for a typical CMS)

#### Option B: S3/Cloudflare R2 (Production Solution)

See `STORAGE_SETUP.md` for complete setup instructions.

```env
# Add to .env:
FILESYSTEM_DISK=s3
AWS_ACCESS_KEY_ID=your_key
AWS_SECRET_ACCESS_KEY=your_secret
AWS_DEFAULT_REGION=us-east-1
AWS_BUCKET=your-bucket-name
AWS_URL=https://your-bucket.s3.amazonaws.com
```

**⚠️ Without persistent storage:** All uploaded images will disappear on redeploy!

---

## 🗄️ Step 3: Database Setup (5 minutes)

### 3.1 Run Migrations

```bash
# For Docker:
docker-compose exec app php artisan migrate --force

# For Railway/production:
railway run php artisan migrate --force
# OR via Railway dashboard: Add "Deploy" command
```

### 3.2 Seed Initial Data (Optional)

**For production:** You probably want a clean database.

**For demo/testing:** Run the seeder to populate sample content:

```bash
docker-compose exec app php artisan db:seed
```

This creates:

-   8 categories
-   20 tags
-   10 sample articles
-   1 admin user (credentials above)

---

## 📧 Step 4: Email Configuration (10 minutes)

### 4.1 Set Up Email Service

**Options:**

1. **Mailtrap.io** (Testing) - FREE
2. **SendGrid** (Production) - 100 emails/day free
3. **Mailgun** (Production) - First 5000 emails free
4. **AWS SES** (Production) - $0.10 per 1000 emails

**Example (SendGrid):**

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.sendgrid.net
MAIL_PORT=587
MAIL_USERNAME=apikey
MAIL_PASSWORD=your_sendgrid_api_key
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@yourdomain.com
MAIL_FROM_NAME="${APP_NAME}"
```

### 4.2 Test Email

```bash
docker-compose exec app php artisan tinker
>>> Mail::raw('Test email', function($msg) { $msg->to('your@email.com')->subject('Test'); });
>>> exit
```

Check your inbox (and spam folder).

---

## ⚡ Step 5: Performance Optimization (5 minutes)

### 5.1 Cache Configuration

```bash
docker-compose exec app php artisan config:cache
docker-compose exec app php artisan route:cache
docker-compose exec app php artisan view:cache
```

**⚠️ Important:** Run these commands after EVERY `.env` change.

### 5.2 Build Production Assets

```bash
docker-compose exec app npm run build
```

This creates optimized CSS/JS files in `/public/build`.

---

## 🌐 Step 6: Domain & SSL (Platform-specific)

### Railway

```bash
railway domain add yourdomain.com
```

Then add DNS records:

```
Type  Name  Value
A     @     (Railway will provide IP)
CNAME www   your-app.up.railway.app
```

### Render

1. Go to Settings → Custom Domains
2. Add your domain
3. Update DNS as instructed
4. SSL certificate is automatic

### DigitalOcean

1. Settings → Domains → Add Domain
2. Update DNS records
3. SSL certificate is automatic

---

## ✅ Step 7: Final Verification

### 7.1 Security Checklist

-   [ ] ✅ New APP_KEY generated
-   [ ] ✅ Admin password changed from default
-   [ ] ✅ Database password changed from 'secret'
-   [ ] ✅ APP_DEBUG=false in .env
-   [ ] ✅ APP_ENV=production in .env
-   [ ] ✅ Storage configured (Volumes or S3)
-   [ ] ✅ Email service configured
-   [ ] ✅ SSL certificate active (https://)

### 7.2 Feature Testing

Test these features on production:

-   [ ] ✅ Can login to `/admin` panel
-   [ ] ✅ Can create a new article
-   [ ] ✅ Can upload images (test persistence after redeploy!)
-   [ ] ✅ Can publish article
-   [ ] ✅ Email notification sent on publish
-   [ ] ✅ Public article page loads
-   [ ] ✅ Search functionality works
-   [ ] ✅ Sitemap accessible at `/sitemap.xml`

### 7.3 Performance Testing

```bash
# Check database connection
docker-compose exec app php artisan tinker
>>> DB::connection()->getPdo();
>>> exit

# Check cache is working
docker-compose exec app php artisan tinker
>>> Cache::put('test', 'value', 60);
>>> Cache::get('test');
>>> exit
```

---

## 🆘 Troubleshooting

### "500 Internal Server Error"

1. Check logs:

    ```bash
    docker-compose logs app
    # OR
    railway logs
    ```

2. Verify APP_KEY is set:

    ```bash
    docker-compose exec app php artisan tinker
    >>> config('app.key');
    ```

3. Clear all caches:
    ```bash
    docker-compose exec app php artisan optimize:clear
    ```

### "Database connection error"

1. Check `.env` credentials match database service
2. For Railway: Use the provided `DATABASE_URL` variable
3. Test connection:
    ```bash
    docker-compose exec app php artisan migrate:status
    ```

### "Uploaded images are 404"

1. **Most common:** Storage not configured (see Step 2)
2. Run storage link:
    ```bash
    docker-compose exec app php artisan storage:link
    ```
3. Check file permissions:
    ```bash
    docker-compose exec app chmod -R 775 storage
    ```

### "Email not sending"

1. Check `.env` mail configuration
2. Verify credentials with your email provider
3. Check logs:
    ```bash
    docker-compose logs app | grep -i mail
    ```

---

## 📊 Monitoring (Recommended)

### Set Up Error Tracking

**Free options:**

1. **Sentry** (5000 events/month free)

    ```bash
    composer require sentry/sentry-laravel
    ```

2. **Flare** (10 errors/month free - Laravel-specific)
    ```bash
    composer require spatie/laravel-ignition
    ```

### Set Up Uptime Monitoring

**Free options:**

1. **UptimeRobot** (50 monitors free)
2. **Pingdom** (Free tier available)
3. **Better Uptime** (10 monitors free)

---

## 🎉 Ready to Launch!

Once all checkboxes are ✅, your application is production-ready!

**Post-Launch:**

1. Monitor error logs for first 24 hours
2. Test all features from a user's perspective
3. Set up daily database backups
4. Document any custom configuration for future reference

---

## 📚 Additional Resources

-   [STORAGE_SETUP.md](STORAGE_SETUP.md) - Detailed storage configuration
-   [DEPLOYMENT_NOTES.md](DEPLOYMENT_NOTES.md) - Platform-specific deployment guides
-   [CREDENTIALS.md](CREDENTIALS.md) - Default credentials and security notes
-   [GET_STARTED.md](GET_STARTED.md) - Development setup

---

**Need help?** Refer to the comprehensive documentation in this project or check the Laravel/Filament community forums.

**Created:** 2 December 2025  
**Last Updated:** 2 December 2025  
**Version:** 1.0
