# Railway File Upload Configuration

## ⚠️ Important: Ephemeral Storage on Railway

Railway uses **ephemeral storage**, which means:

-   Files uploaded to `storage/app/public` are **lost on every deployment/restart**
-   This is a limitation of Railway's containerized infrastructure
-   **NOT recommended for production** file storage

## Current Setup (Development Only)

The startup script (`railway-start.sh`) creates:

-   Storage directories: `storage/app/public`
-   Storage symlink: `public/storage` → `storage/app/public`
-   Proper permissions (775)

**This works for testing** but files will disappear on restart!

## Production Solution: Use Cloud Storage

For production, you **MUST** use persistent cloud storage like:

### Option 1: AWS S3 (Recommended)

1. **Create an S3 bucket** on AWS
2. **Add these environment variables** in Railway:

    ```bash
    FILESYSTEM_DISK=s3
    AWS_ACCESS_KEY_ID=your_access_key
    AWS_SECRET_ACCESS_KEY=your_secret_key
    AWS_DEFAULT_REGION=us-east-1
    AWS_BUCKET=your-bucket-name
    ```

3. **Install AWS SDK** (already in composer.json):
    ```bash
    composer require league/flysystem-aws-s3-v3
    ```

### Option 2: Cloudinary

1. **Create Cloudinary account** (free tier available)
2. **Install package**:
    ```bash
    composer require cloudinary/cloudinary_php
    ```
3. **Configure in Railway variables**

### Option 3: Railway Volumes (Limited)

Railway offers persistent volumes, but:

-   Not available on all plans
-   Limited storage capacity
-   More expensive than S3

## Temporary Testing on Railway

If you just want to **test uploads** temporarily:

1. ✅ Storage directories are created automatically
2. ✅ Storage symlink is created
3. ⚠️ Uploads work until next deployment
4. ❌ Files lost on restart/redeploy

## Checking Storage Status

The startup script logs:

```
Setting up storage...
Creating storage symlink...
```

Look for these messages in Railway deployment logs.

## Migration Path

**For now (development):**

-   Local storage works for testing
-   Files disappear on redeploy

**For production:**

-   Switch `FILESYSTEM_DISK=s3` in Railway
-   Add AWS credentials
-   No code changes needed (Laravel handles it)

## Files Already Configured

-   ✅ `config/filesystems.php` - S3 config ready
-   ✅ `railway-start.sh` - Creates storage directories
-   ✅ Media library ready (Spatie)
-   ⏳ Just add S3 credentials when ready

## Next Steps

1. **For testing:** Current setup works (temporary files)
2. **For production:** Set up S3 bucket and add credentials
3. **No code changes required** - just environment variables!
