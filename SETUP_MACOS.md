# Step-by-Step Setup for macOS

## Prerequisites Installation

### 1. Install Homebrew

Open Terminal and run:

```bash
/bin/bash -c "$(curl -fsSL https://raw.githubusercontent.com/Homebrew/install/HEAD/install.sh)"
```

**After installation**, follow the instructions to add Homebrew to your PATH.

### 2. Install PHP 8.2

```bash
brew install php@8.2
```

Add to PATH (add to ~/.zshrc):

```bash
echo 'export PATH="/opt/homebrew/opt/php@8.2/bin:$PATH"' >> ~/.zshrc
source ~/.zshrc
```

Verify:

```bash
php -v
```

### 3. Install Composer

```bash
brew install composer
```

Verify:

```bash
composer -V
```

### 4. Install Node.js

```bash
brew install node@20
```

Verify:

```bash
node -v
npm -v
```

### 5. Install PostgreSQL

```bash
brew install postgresql@15
brew services start postgresql@15
```

Verify:

```bash
psql --version
```

## Laravel CMS Installation

Once all prerequisites are installed, run:

```bash
cd /Users/princeps/TheGoodBeaconNewsOutlet
./setup.sh
```

Or follow manual steps in [INSTALLATION.md](INSTALLATION.md)

## After Installation

1. **Run migrations**:

   ```bash
   php artisan migrate
   ```

2. **Create admin user**:

   ```bash
   php artisan make:filament-user
   ```

3. **Start servers**:

   ```bash
   # Terminal 1
   php artisan serve

   # Terminal 2
   npm run dev
   ```

4. **Visit**:
   - Admin: http://localhost:8000/admin
   - Frontend: http://localhost:8000

## Troubleshooting

If you encounter any issues:

1. Make sure all prerequisites are installed
2. Check that services are running: `brew services list`
3. Verify PHP version: `php -v` (should be 8.2+)
4. Check Composer: `composer -V`
5. Check Node: `node -v` (should be 20+)

For detailed help, see [INSTALLATION.md](INSTALLATION.md)
