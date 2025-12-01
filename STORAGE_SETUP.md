# Storage Configuration for Railway Deployment

## The Problem

Railway containers use **ephemeral filesystems** - any files uploaded to `storage/app/public` are **lost when the container restarts or redeploys**. This is why your images show 404 errors after deployment.

## Solution 1: Railway Persistent Volumes (Quick Fix)

### Steps:

1. **Go to Railway Dashboard**

    - Visit: https://railway.app/
    - Select your project: `natural-dream`
    - Click on service: `TheGoodBeaconNewsOutlet`

2. **Add a Volume**

    - Click the **"Volumes"** tab
    - Click **"New Volume"**
    - Configure:
        ```
        Mount Path: /var/www/storage/app/public
        Size: 10 GB (adjust based on your needs)
        ```
    - Click **"Add Volume"**

3. **Redeploy**
    - Railway will automatically redeploy
    - The volume will now persist across deployments
    - All uploaded images will remain accessible!

### Pros:

✅ Simple setup (no code changes needed)
✅ Works immediately
✅ Files persist across deployments

### Cons:

❌ Single region storage (no CDN)
❌ Limited by volume size
❌ Slower for global users

---

## Solution 2: AWS S3 (Production Recommended)

For production, use S3 or S3-compatible storage (Cloudflare R2, DigitalOcean Spaces, Backblaze B2).

### Steps:

#### 1. Set up S3 Bucket (or compatible service)

**Option A: AWS S3**

-   Create bucket at https://console.aws.amazon.com/s3/
-   Set bucket to public read access
-   Create IAM user with S3 permissions
-   Get Access Key ID and Secret Access Key

**Option B: Cloudflare R2** (Cheaper, S3-compatible)

-   Create bucket at https://dash.cloudflare.com/
-   Get Access Key ID and Secret Access Key
-   S3-compatible, but cheaper than AWS

**Option C: DigitalOcean Spaces**

-   Create Space at https://cloud.digitalocean.com/spaces
-   Get Access Key and Secret Key

#### 2. Add Environment Variables in Railway

Go to your Railway service settings and add:

```bash
AWS_ACCESS_KEY_ID=your_access_key
AWS_SECRET_ACCESS_KEY=your_secret_key
AWS_DEFAULT_REGION=us-east-1  # or your region
AWS_BUCKET=your-bucket-name
AWS_URL=https://your-bucket.s3.amazonaws.com  # or your CDN URL
AWS_ENDPOINT=  # leave empty for AWS S3, set for alternatives

# For Cloudflare R2:
# AWS_ENDPOINT=https://ACCOUNT_ID.r2.cloudflarestorage.com
# AWS_URL=https://your-r2-url.com

FILESYSTEM_DISK=s3
MEDIA_DISK=s3
```

#### 3. Update Media Library Config

The config is already set up - just change the environment variable:

```bash
FILESYSTEM_DISK=s3
```

#### 4. Redeploy

Push any change to trigger a deployment, and all uploads will now go to S3!

### Pros:

✅ CDN delivery (fast worldwide)
✅ Unlimited storage
✅ Automatic backups
✅ Professional solution
✅ Independent of your app server

### Cons:

❌ Costs money (but very cheap)
❌ Requires setup

---

## Solution 3: Cloudinary (Image-Specific)

If you only need image storage with built-in transformations:

### Steps:

1. **Sign up at Cloudinary**

    - https://cloudinary.com/ (Free tier: 25GB storage, 25GB bandwidth/month)

2. **Install Package**

    ```bash
    composer require cloudinary-labs/cloudinary-laravel
    php artisan vendor:publish --provider="CloudinaryLabs\CloudinaryLaravel\CloudinaryServiceProvider"
    ```

3. **Add to Railway Environment**

    ```bash
    CLOUDINARY_URL=cloudinary://API_KEY:API_SECRET@CLOUD_NAME
    FILESYSTEM_DISK=cloudinary
    ```

4. **Configure Filesystem** (add to `config/filesystems.php`):
    ```php
    'cloudinary' => [
        'driver' => 'cloudinary',
        'api_key' => env('CLOUDINARY_API_KEY'),
        'api_secret' => env('CLOUDINARY_API_SECRET'),
        'cloud_name' => env('CLOUDINARY_CLOUD_NAME'),
    ],
    ```

### Pros:

✅ Free tier available
✅ Automatic image optimization
✅ Built-in CDN
✅ Image transformations on-the-fly

### Cons:

❌ Vendor lock-in
❌ Limited to images/videos

---

## Current Status

Your application is currently configured to use **local storage** (`public` disk), which is causing the 404 errors on Railway.

### Quick Fix (Do This Now):

**Add a Railway Volume** to make storage persistent.

### Long-term Solution:

**Switch to S3** or S3-compatible storage for production reliability.

---

## Testing After Setup

After implementing either solution:

1. Upload an image to an article
2. Note the URL (should be `/storage/8/uuid.jpeg` for volumes, or full S3 URL for S3)
3. Redeploy your application: `git commit --allow-empty -m "Test deploy" && git push`
4. Check if the image still displays after redeployment

If using volumes: Image should still work ✅
If using S3: Image URL will be S3 URL and always work ✅

---

## Need Help?

-   Railway Volumes: https://docs.railway.app/reference/volumes
-   AWS S3: https://aws.amazon.com/s3/
-   Cloudflare R2: https://www.cloudflare.com/products/r2/
-   Laravel Filesystems: https://laravel.com/docs/filesystem
