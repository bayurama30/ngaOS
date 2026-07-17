<?php

/**
 * Script untuk membuat user baru di NgaOS
 * Jalankan sekali lalu HAPUS file ini!
 *
 * Akses: https://ngaos.io/create-user.php
 */

// Load Laravel bootstrap
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$kernel->handle(Illuminate\Http\Request::capture());

// Cek apakah sudah ada user
$existingUser = \App\Models\User::first();
if ($existingUser) {
    echo "User sudah ada: {$existingUser->email}\n";
    echo "Silakan login di https://ngaos.io\n";
    exit;
}

// Buat user baru
$user = \App\Models\User::create([
    'name' => 'Admin',
    'email' => 'admin@ngaos.io',
    'password' => bcrypt('password123'),
]);

echo "=== User Berhasil Dibuat ===\n";
echo "Email: admin@ngaos.io\n";
echo "Password: password123\n";
echo "\nSilakan login di https://ngaos.io\n";
echo "\n!!! PENTING: Hapus file ini setelah login !!!\n";
