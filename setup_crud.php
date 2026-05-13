<?php
/**
 * Setup Script untuk CRUD Event Admin
 * Jalankan: php setup_crud.php
 */

echo "=== Setup CRUD Event Dashboard ===\n\n";

// Autoload Laravel
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);

// Migrate
echo "[1/3] Menjalankan Migrations...\n";
$kernel->call('migrate:refresh', ['--force' => true, '--no-interaction' => true]);
echo "✓ Migrations selesai\n\n";

// Seed
echo "[2/3] Menjalankan Seeder...\n";
$kernel->call('db:seed', ['--class' => 'Database\\Seeders\\DatabaseSeeder', '--force' => true]);
echo "✓ Seeding selesai\n\n";

// Storage Link
echo "[3/3] Membuat Storage Link...\n";
$kernel->call('storage:link');
echo "✓ Storage link selesai\n\n";

echo "=== Setup Selesai! ===\n";
echo "Anda sekarang bisa mengakses: http://localhost/admin\n";
echo "Email: admin@amikom.ac.id\n";
echo "Password: password\n";
?>
