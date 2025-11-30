# Email Configuration Guide

## ✅ Email System Setup Complete

Your Laravel CMS now has a fully functional email notification system!

---

## 📧 Features Implemented

### 1. **Article Published Notifications**

-   ✅ Automatic email when articles are published
-   ✅ Beautiful HTML email templates
-   ✅ Queue support for better performance
-   ✅ Database notifications for in-app alerts

### 2. **Email Components**

-   ✅ `ArticlePublishedNotification` - Notification class
-   ✅ `ArticlePublishedMail` - Mailable class
-   ✅ `ArticleObserver` - Auto-trigger on publish
-   ✅ `TestEmailCommand` - Test email functionality
-   ✅ Beautiful Markdown email template

### 3. **Database Tables**

-   ✅ `notifications` table created
-   ✅ Stores notification history for users

---

## 🔧 Current Configuration

### Development (Local)

Your `.env` is currently set to use the **log driver** for testing:

```env
MAIL_MAILER=log
```

Emails are written to: `storage/logs/laravel.log`

---

## 🚀 Production Email Providers

### Option 1: Mailtrap (Development & Staging)

**Free Tier:** 500 emails/month

```env
MAIL_MAILER=smtp
MAIL_HOST=sandbox.smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=your_mailtrap_username
MAIL_PASSWORD=your_mailtrap_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="news@goodbeacon.com"
MAIL_FROM_NAME="The Good Beacon News"
```

**Setup Steps:**

1. Go to https://mailtrap.io
2. Sign up for free account
3. Create a new inbox
4. Copy SMTP credentials
5. Update `.env` with credentials

---

### Option 2: Gmail SMTP (Free - Personal Use)

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your-gmail@gmail.com
MAIL_PASSWORD=your-app-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="your-gmail@gmail.com"
MAIL_FROM_NAME="The Good Beacon News"
```

**Setup Steps:**

1. Go to https://myaccount.google.com/security
2. Enable 2-Factor Authentication
3. Generate App Password: https://myaccount.google.com/apppasswords
4. Use the 16-character app password in `.env`

**Limits:** 500 emails/day

---

### Option 3: SendGrid (Production - Recommended)

**Free Tier:** 100 emails/day forever

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.sendgrid.net
MAIL_PORT=587
MAIL_USERNAME=apikey
MAIL_PASSWORD=your_sendgrid_api_key
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="news@goodbeacon.com"
MAIL_FROM_NAME="The Good Beacon News"
```

**Setup Steps:**

1. Sign up at https://sendgrid.com
2. Verify your sender identity
3. Create API Key
4. Update `.env` with API key

---

### Option 4: Mailgun (Production)

**Free Tier:** 5,000 emails/month for 3 months

```env
MAIL_MAILER=mailgun
MAILGUN_DOMAIN=mg.yourdomain.com
MAILGUN_SECRET=your_mailgun_api_key
MAILGUN_ENDPOINT=api.mailgun.net
MAIL_FROM_ADDRESS="news@goodbeacon.com"
MAIL_FROM_NAME="The Good Beacon News"
```

**Setup Steps:**

1. Sign up at https://mailgun.com
2. Add and verify your domain
3. Get API credentials
4. Update `.env`

---

### Option 5: Amazon SES (Production - Cheapest)

**Cost:** $0.10 per 1,000 emails

```env
MAIL_MAILER=ses
AWS_ACCESS_KEY_ID=your_access_key
AWS_SECRET_ACCESS_KEY=your_secret_key
AWS_DEFAULT_REGION=us-east-1
MAIL_FROM_ADDRESS="news@goodbeacon.com"
MAIL_FROM_NAME="The Good Beacon News"
```

---

## 📝 Testing Emails

### Test Email Command

Send a test email to any address:

```bash
# Using Docker
docker compose exec app php artisan email:test your-email@example.com

# Direct (if PHP is installed locally)
php artisan email:test your-email@example.com
```

### Test with Tinker

```bash
docker compose exec app php artisan tinker
```

Then run:

```php
use App\Mail\ArticlePublishedMail;
use App\Models\Article;
use Illuminate\Support\Facades\Mail;

$article = Article::first();
Mail::to('test@example.com')->send(new ArticlePublishedMail($article));
```

---

## 🔔 How Notifications Work

### Automatic Notifications

When an article is published, the system automatically:

1. **Article Observer** detects the status change
2. **Notification** is queued for all users
3. **Email** is sent to each user
4. **Database record** is created for in-app notifications

### Manual Notifications

You can manually trigger notifications:

```php
use App\Models\User;
use App\Models\Article;
use App\Notifications\ArticlePublishedNotification;

$article = Article::find(1);
$users = User::all();

// Send to all users
Notification::send($users, new ArticlePublishedNotification($article));

// Send to specific user
$user = User::find(1);
$user->notify(new ArticlePublishedNotification($article));
```

---

## 📊 Queue Configuration

Emails are queued for better performance. Configure your queue:

### Using Database Queue (Current)

```env
QUEUE_CONNECTION=database
```

Run the queue worker:

```bash
docker compose exec app php artisan queue:work
```

### Using Redis Queue (Better Performance)

```env
QUEUE_CONNECTION=redis
REDIS_HOST=good_beacon_redis
REDIS_PORT=6379
```

---

## 🎨 Customizing Email Templates

### Email Template Location

`resources/views/emails/article-published.blade.php`

### Customize the Look

Laravel uses Markdown for email templates. Edit the file to customize:

```blade
<x-mail::message>
# Your Custom Header

Your custom content here...

<x-mail::button :url="$url">
Custom Button Text
</x-mail::button>

<x-mail::panel>
Special highlighted content
</x-mail::panel>

<x-mail::table>
| Header 1 | Header 2 |
|----------|----------|
| Cell 1   | Cell 2   |
</x-mail::table>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
```

### Publish Email Templates

To fully customize email styles:

```bash
docker compose exec app php artisan vendor:publish --tag=laravel-mail
```

This creates: `resources/views/vendor/mail/`

---

## 🔐 Security Best Practices

### 1. Never Commit Email Credentials

✅ Already in `.gitignore`:

```
.env
```

### 2. Use Environment Variables

❌ Never hardcode:

```php
Mail::to('user@example.com')->send(...); // Bad
```

✅ Always use config:

```php
Mail::to(config('mail.from.address'))->send(...); // Good
```

### 3. Verify Sender Identity

-   **SendGrid**: Verify your domain
-   **Mailgun**: Add SPF/DKIM records
-   **Gmail**: Use App Passwords, not real password

### 4. Rate Limiting

Add rate limiting to prevent abuse:

```php
// In ArticleObserver.php
use Illuminate\Support\Facades\RateLimiter;

protected function notifySubscribers(Article $article): void
{
    $executed = RateLimiter::attempt(
        'send-article-notification:'.$article->id,
        $perMinute = 1,
        function() use ($article) {
            $users = User::all();
            Notification::send($users, new ArticlePublishedNotification($article));
        }
    );
}
```

---

## 📈 Monitoring & Logging

### Check Email Logs

```bash
# View recent emails
docker compose exec app tail -f storage/logs/laravel.log

# Search for email errors
docker compose exec app grep "Failed to send" storage/logs/laravel.log
```

### Queue Monitoring

```bash
# Check queue jobs
docker compose exec app php artisan queue:failed

# Retry failed jobs
docker compose exec app php artisan queue:retry all

# Clear failed jobs
docker compose exec app php artisan queue:flush
```

---

## 🎯 Next Steps

### 1. Add Newsletter Subscription

Create a subscribers table:

```bash
docker compose exec app php artisan make:migration create_subscribers_table
```

### 2. Add Email Preferences

Let users choose notification types:

```bash
docker compose exec app php artisan make:migration add_email_preferences_to_users
```

### 3. Send Weekly Digest

Create a command to send weekly article summaries:

```bash
docker compose exec app php artisan make:command SendWeeklyDigest
```

### 4. Email Analytics

Track open rates and click-through rates using services like:

-   SendGrid Email Activity
-   Mailgun Tracking
-   PostMark Analytics

---

## 🆘 Troubleshooting

### "Connection refused" Error

**Problem:** Can't connect to SMTP server

**Solutions:**

1. Check firewall settings
2. Verify SMTP credentials
3. Try different port (587, 465, 2525)
4. Check if SMTP service is running

### "Authentication failed" Error

**Problem:** Wrong credentials

**Solutions:**

1. Verify username/password
2. For Gmail, use App Password
3. Check if 2FA is enabled
4. Regenerate API key

### Emails Not Sending

**Problem:** No errors but emails don't arrive

**Solutions:**

1. Check spam folder
2. Verify sender email is authorized
3. Check queue is running: `php artisan queue:work`
4. Check logs: `storage/logs/laravel.log`
5. Test with: `php artisan email:test`

### Queue Not Processing

**Problem:** Emails stuck in queue

**Solutions:**

```bash
# Check queue
docker compose exec app php artisan queue:work

# Or use supervisor for production
docker compose exec app php artisan queue:listen

# Clear failed jobs
docker compose exec app php artisan queue:retry all
```

---

## 📚 Documentation Links

-   [Laravel Mail Documentation](https://laravel.com/docs/12.x/mail)
-   [Laravel Notifications](https://laravel.com/docs/12.x/notifications)
-   [Markdown Mail](https://laravel.com/docs/12.x/mail#markdown-mailables)
-   [Queue Workers](https://laravel.com/docs/12.x/queues)

---

## ✨ Email Features Summary

| Feature                | Status | Description                  |
| ---------------------- | ------ | ---------------------------- |
| SMTP Configuration     | ✅     | Multiple providers supported |
| Article Notifications  | ✅     | Auto-send on publish         |
| Queue Support          | ✅     | Better performance           |
| Database Notifications | ✅     | In-app alerts                |
| Beautiful Templates    | ✅     | Markdown-based HTML          |
| Test Command           | ✅     | Easy testing                 |
| Observer Pattern       | ✅     | Auto-trigger events          |

---

**Last Updated:** 30 November 2025  
**Status:** ✅ Production Ready
