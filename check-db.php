#!/usr/bin/env php
<?php

echo "=== DATABASE CONNECTION CHECK ===\n\n";

// Check environment variables
echo "Environment Variables:\n";
echo "DATABASE_URL: " . (getenv('DATABASE_URL') ? 'SET (hidden for security)' : 'NOT SET') . "\n";
echo "DB_CONNECTION: " . (getenv('DB_CONNECTION') ?: 'NOT SET') . "\n";
echo "DB_HOST: " . (getenv('DB_HOST') ?: 'NOT SET') . "\n";
echo "DB_PORT: " . (getenv('DB_PORT') ?: 'NOT SET') . "\n";
echo "DB_DATABASE: " . (getenv('DB_DATABASE') ?: 'NOT SET') . "\n";
echo "\n";

// Try to parse DATABASE_URL
if ($databaseUrl = getenv('DATABASE_URL')) {
  echo "Parsing DATABASE_URL...\n";
  $url = parse_url($databaseUrl);

  echo "Scheme: " . ($url['scheme'] ?? 'NOT FOUND') . "\n";
  echo "Host: " . ($url['host'] ?? 'NOT FOUND') . "\n";
  echo "Port: " . ($url['port'] ?? 'NOT FOUND') . "\n";
  echo "Database: " . (isset($url['path']) ? ltrim($url['path'], '/') : 'NOT FOUND') . "\n";
  echo "User: " . ($url['user'] ?? 'NOT FOUND') . "\n";
  echo "Password: " . (isset($url['pass']) ? 'SET (hidden)' : 'NOT SET') . "\n";
  echo "\n";
}

// Try to connect
echo "Attempting database connection...\n";

require __DIR__ . '/vendor/autoload.php';

use Illuminate\Support\Facades\DB;

try {
  $app = require_once __DIR__ . '/bootstrap/app.php';
  $app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

  $pdo = DB::connection()->getPdo();
  echo "✅ SUCCESS: Database connection established!\n";
  echo "Driver: " . DB::connection()->getDriverName() . "\n";
  echo "Database: " . DB::connection()->getDatabaseName() . "\n";
  exit(0);
} catch (\Exception $e) {
  echo "❌ ERROR: Database connection failed!\n";
  echo "Error: " . $e->getMessage() . "\n";
  echo "\n";
  echo "This might be because:\n";
  echo "1. PostgreSQL service is not started\n";
  echo "2. DATABASE_URL is not set in Railway environment\n";
  echo "3. PostgreSQL service is not linked to this app\n";
  echo "4. Network timeout or firewall issue\n";

  exit(1);
}
