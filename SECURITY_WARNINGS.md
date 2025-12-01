# ⚠️ CRITICAL SECURITY WARNINGS

## 🔴 MUST CHANGE BEFORE DEPLOYMENT

This application ships with **default credentials for demonstration purposes only**. These MUST be changed before deploying to production!

---

## 🔑 Default Credentials (INSECURE!)

### Admin Panel Login

```
URL:      https://yourdomain.com/admin
Email:    admin@goodbeacon.com
Password: admin123
```

**⚠️ ACTION REQUIRED:** Change this password immediately after first login!

### Database (Docker)

```
Username: postgres
Password: secret
Database: good_beacon_cms
```

**⚠️ ACTION REQUIRED:** Change database password before production deployment!

---

## 🚨 Security Checklist

Before deploying to production, verify ALL of these:

### 1. Environment Configuration

-   [ ] **APP_KEY** - New key generated (run `php artisan key:generate`)
-   [ ] **APP_ENV** - Set to `production` (not `local`)
-   [ ] **APP_DEBUG** - Set to `false` (never `true` in production!)
-   [ ] **APP_URL** - Set to your actual domain with `https://`

### 2. Authentication

-   [ ] **Admin password** - Changed from default `admin123`
-   [ ] **Admin email** - Consider changing from `admin@goodbeacon.com`
-   [ ] **Password policy** - Review minimum requirements (currently 8 chars)

### 3. Database

-   [ ] **DB_PASSWORD** - Changed from default `secret`
-   [ ] **Database user** - Not using root/postgres in production
-   [ ] **Database backups** - Automated backup schedule configured
-   [ ] **Database encryption** - SSL connection enabled (if supported)

### 4. File Storage

-   [ ] **Storage persistence** - Railway Volumes or S3 configured
-   [ ] **File permissions** - `storage/` and `bootstrap/cache/` writable
-   [ ] **Public storage link** - Created with `php artisan storage:link`
-   [ ] **Upload limits** - Review `php.ini` settings (currently 64MB)

### 5. Email Security

-   [ ] **MAIL_FROM_ADDRESS** - Set to your actual domain email
-   [ ] **Email provider** - Production SMTP configured (not `log` driver)
-   [ ] **DKIM/SPF** - DNS records configured for deliverability
-   [ ] **Email rate limits** - Provider limits understood

### 6. Session & Cache

-   [ ] **SESSION_DRIVER** - Using database/redis (not `file` in production)
-   [ ] **CACHE_DRIVER** - Using redis/memcached (not `file` in production)
-   [ ] **Session lifetime** - Appropriate timeout configured
-   [ ] **Secure cookies** - HTTPS enforced for production

### 7. HTTPS & Domain

-   [ ] **SSL Certificate** - Valid certificate installed
-   [ ] **Force HTTPS** - Redirect HTTP to HTTPS enabled
-   [ ] **HSTS Header** - Strict-Transport-Security configured
-   [ ] **DNS configured** - A/CNAME records pointing correctly

### 8. Logging & Monitoring

-   [ ] **LOG_LEVEL** - Set to `error` or `warning` (not `debug`)
-   [ ] **Error tracking** - Sentry/Flare/Bugsnag configured
-   [ ] **Uptime monitoring** - Service configured (UptimeRobot, etc.)
-   [ ] **Log rotation** - Prevent logs from filling disk space

### 9. Additional Security

-   [ ] **Rate limiting** - API/login endpoints protected
-   [ ] **CORS** - Properly configured if using external APIs
-   [ ] **CSP Headers** - Content Security Policy configured
-   [ ] **XSS Protection** - Enabled (Laravel default)
-   [ ] **CSRF Protection** - Enabled (Laravel default)

---

## 🛡️ Quick Security Setup

Run these commands immediately after deployment:

```bash
# 1. Generate new application key
php artisan key:generate

# 2. Change admin password
php artisan tinker
>>> $user = User::where('email', 'admin@goodbeacon.com')->first();
>>> $user->password = Hash::make('YourNewSecurePassword!');
>>> $user->save();
>>> exit

# 3. Clear and cache configuration
php artisan config:cache
php artisan route:cache
php artisan view:cache

# 4. Set proper permissions
chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache

# 5. Verify security settings
php artisan about
```

---

## 🚫 Common Security Mistakes

### ❌ DON'T DO THIS:

1. **Leave APP_DEBUG=true in production**
    - Exposes sensitive data, stack traces, environment variables
2. **Use default passwords**

    - `admin123`, `secret`, `password` are the first passwords attackers try

3. **Commit `.env` file to Git**
    - Contains secrets, database credentials, API keys
4. **Disable CSRF protection**

    - Leaves you vulnerable to Cross-Site Request Forgery attacks

5. **Use `file` driver for sessions in production**

    - Not scalable, can cause race conditions

6. **Skip database backups**

    - One hardware failure = all data lost

7. **Use HTTP instead of HTTPS**
    - Credentials and data transmitted in plain text

---

## 📋 Security Audit Checklist

Run this audit weekly/monthly:

### Application Security

```bash
# Check for Laravel security updates
composer outdated

# Check for known vulnerabilities
composer audit

# Review Laravel logs for suspicious activity
tail -n 100 storage/logs/laravel.log

# Check for failed login attempts
php artisan tinker
>>> User::whereNotNull('email_verified_at')->count();
```

### Server Security

```bash
# Check for security updates (Ubuntu/Debian)
apt update && apt list --upgradable

# Review server access logs
tail -n 100 /var/log/nginx/access.log

# Check for unusual network connections
netstat -tulpn | grep ESTABLISHED

# Verify firewall rules
ufw status
```

### Database Security

```bash
# Check PostgreSQL logs
tail -n 100 /var/log/postgresql/postgresql-15-main.log

# Review database users
psql -U postgres -c "SELECT usename, usesuper FROM pg_user;"

# Check for unauthorized schema changes
php artisan migrate:status
```

---

## 🆘 Security Incident Response

If you suspect a security breach:

### 1. Immediate Actions (First 5 minutes)

```bash
# 1. Take the site offline
php artisan down --secret="recovery-key-$(date +%s)"

# 2. Change all passwords
# - Admin panel
# - Database
# - Email/SMTP
# - API keys

# 3. Backup current state for forensics
tar -czf security-incident-$(date +%Y%m%d-%H%M%S).tar.gz storage/logs database
```

### 2. Investigation (First 30 minutes)

-   Review logs for unusual activity
-   Check database for unauthorized changes
-   Review file system for modified files
-   Check for new admin users
-   Review server access logs

### 3. Recovery (Next 2 hours)

-   Restore from known-good backup
-   Update all dependencies
-   Patch identified vulnerabilities
-   Review and tighten security settings
-   Bring site back online

### 4. Post-Incident (Within 24 hours)

-   Document the incident
-   Notify affected users (if data was compromised)
-   Implement additional security measures
-   Set up monitoring to detect similar attacks

---

## 📞 Security Resources

-   **Laravel Security Best Practices**: https://laravel.com/docs/security
-   **OWASP Top 10**: https://owasp.org/www-project-top-ten/
-   **Security Headers**: https://securityheaders.com/
-   **SSL Test**: https://www.ssllabs.com/ssltest/
-   **Laravel Security Advisories**: https://github.com/laravel/framework/security/advisories

---

## ✅ Final Security Verification

Before going live, verify this command shows secure settings:

```bash
php artisan about
```

**Expected output:**

```
Environment ........................... production
Debug Mode ............................ OFF
URL ................................... https://yourdomain.com
Maintenance Mode ...................... OFF

Cache
  Config ............................... CACHED
  Events ............................... NOT CACHED
  Routes ............................... CACHED
  Views ................................ CACHED

Drivers
  Broadcasting ......................... log
  Cache ................................ redis
  Database ............................. pgsql
  Logs ................................. stack / daily
  Mail ................................. smtp
  Queue ................................ redis
  Session .............................. redis
```

---

**Remember:** Security is not a one-time task. Regular audits, updates, and monitoring are essential for maintaining a secure application.

**Last Updated:** 2 December 2025
