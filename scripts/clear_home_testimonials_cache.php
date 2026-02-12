<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\Cache;
use App\Models\School;

$prefixes = ['home_testimonials'];
$schools = School::all();
foreach ($schools as $s) {
    $prefixes[] = 'home_testimonials_school_' . md5($s->name);
    $prefixes[] = 'home_testimonials_school_like_' . md5($s->name);
}

foreach ($prefixes as $k) {
    $had = Cache::has($k) ? 'YES' : 'NO';
    echo "Key: $k - Present before: $had\n";
    if (Cache::has($k)) {
        Cache::forget($k);
        echo "-> Forgotten $k\n";
    }
}

echo "Done clearing home_testimonials* cache keys.\n";