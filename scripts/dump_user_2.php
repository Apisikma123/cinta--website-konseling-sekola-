<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$u = App\Models\User::find(2);
if (!$u) {
    echo "NO_USER\n";
} else {
    echo "id: {$u->id}\n";
    echo "name: {$u->name}\n";
    echo "email: {$u->email}\n";
    echo "role: {$u->role}\n";
    echo "school: {$u->school}\n";
    echo "is_approved: " . ($u->is_approved ? '1' : '0') . "\n";
}
