<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;

$teachers = User::where('role', 'teacher')->get();
if ($teachers->isEmpty()) {
    echo "NO_TEACHERS\n";
    exit(0);
}

foreach ($teachers as $t) {
    echo implode('|', [
        $t->id,
        $t->name,
        $t->email,
        $t->school ?? 'NULL'
    ]) . PHP_EOL;
}
