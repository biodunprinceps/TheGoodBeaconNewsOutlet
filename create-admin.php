<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\User;
use Illuminate\Support\Facades\Hash;

// Check if admin user already exists
$adminExists = User::where('email', 'admin@goodbeacon.com')->exists();

if (!$adminExists) {
  User::create([
    'name' => 'Admin',
    'email' => 'admin@goodbeacon.com',
    'password' => Hash::make('admin123'),
  ]);

  echo "Admin user created successfully!\n";
  echo "Email: admin@goodbeacon.com\n";
  echo "Password: admin123\n";
} else {
  echo "Admin user already exists.\n";
}
