# Railway File Upload Fix

## Issue

Livewire file uploads failing with 401 Unauthorized error on Railway.

## Root Cause

The error happens when:

1. Livewire generates signed URLs for temporary file uploads
2. The signature validation fails (401 Unauthorized)
3. Common causes:
    - APP_KEY mismatch or not set
    - Session configuration issues
    - CSRF token problems
    - Middleware blocking requests

## Railway Environment Variables Required

Make sure these are set in Railway:

```bash
# CRITICAL: App key must be set and consistent
APP_KEY=base64:your_generated_key_here

# Session settings for Railway
SESSION_DRIVER=database
SESSION_SECURE_COOKIE=true  # Railway uses HTTPS
SESSION_DOMAIN=null

# Filesystem for uploads
FILESYSTEM_DISK=public

# Cache (important for signed URLs)
CACHE_STORE=database
```

## How to Generate APP_KEY

If APP_KEY is not set or causing issues:

1. In Railway, run this command once:

    ```bash
    php artisan key:generate --show
    ```

2. Copy the output (looks like: `base64:xxxxx...`)

3. Add it as `APP_KEY` environment variable in Railway

4. Redeploy

## Configuration Changes Made

1. **config/livewire.php**:

    - Set `disk` to `'public'`
    - Increased `max_upload_time` to 30 minutes
    - Set `middleware` to `'web'` (removes throttle that causes 401)
    - Increased file size to 50MB

2. **railway-start.sh**:

    - Creates `storage/app/public/livewire-tmp` directory
    - Sets proper permissions (775)

3. **config/media-library.php**:
    - Disabled queue conversions
    - Increased max file size to 50MB

## Testing Locally

To test if uploads work:

```bash
# In your local Docker environment
docker-compose exec app php artisan tinker
>>> config('livewire.temporary_file_upload.disk')
// Should return: "public"
```

## Troubleshooting

If uploads still fail:

1. **Check Railway logs** for exact error
2. **Verify APP_KEY** is set in Railway variables
3. **Clear config cache**: Deploy will run `php artisan config:cache`
4. **Check storage permissions**: Logs should show "Setting up storage..."
5. **Test session**: Try logging into /admin - if that works, session is fine

## Filament-Specific Issues

Filament uses Livewire for file uploads. If issues persist:

1. Check `app/Filament/Resources/ArticleResource.php`
2. Ensure `FileUpload` fields use:
    ```php
    FileUpload::make('featured_image')
        ->disk('public')
        ->directory('articles')
    ```

## Known Limitations

-   **Ephemeral storage**: Files lost on Railway restart (use S3 for production)
-   **Upload timeout**: 30 minutes max (configurable in livewire.php)
-   **File size**: 50MB max (configurable)

## Production Solution

For production, switch to S3:

```bash
# Railway variables
FILESYSTEM_DISK=s3
AWS_ACCESS_KEY_ID=your_key
AWS_SECRET_ACCESS_KEY=your_secret
AWS_DEFAULT_REGION=us-east-1
AWS_BUCKET=your-bucket
```

No code changes needed - Laravel/Livewire handles it automatically.
