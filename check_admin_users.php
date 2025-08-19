<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Admin;

echo "=== Checking Admin Users ===\n";

$admins = Admin::all(['email', 'first_name', 'last_name']);

if ($admins->count() > 0) {
    echo "Found " . $admins->count() . " admin user(s):\n";
    foreach ($admins as $admin) {
        echo "- Email: " . $admin->email . "\n";
        echo "  Name: " . $admin->first_name . " " . $admin->last_name . "\n";
        echo "  ID: " . $admin->id . "\n\n";
    }
} else {
    echo "No admin users found in database.\n";
    echo "You may need to run: php artisan db:seed --class=AdminUserSeeder\n";
}

echo "=== End ===\n"; 