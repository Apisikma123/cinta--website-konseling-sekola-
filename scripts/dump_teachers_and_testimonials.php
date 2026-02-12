<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use App\Models\Testimonial;

echo "--- TEACHERS ---\n";
$teachers = User::whereIn('role', ['guru','teacher'])->get();
if ($teachers->isEmpty()) {
    echo "NO TEACHERS FOUND\n";
} else {
    foreach ($teachers as $t) {
        echo implode(' | ', ["id:{$t->id}", "name:{$t->name}", "email:{$t->email}", "school:" . ($t->school ?? 'NULL')]) . PHP_EOL;
    }
}

echo "\n--- TESTIMONIALS (all) ---\n";
$testimonials = Testimonial::with('report')->get();
if ($testimonials->isEmpty()) {
    echo "NO_TESTIMONIALS\n";
} else {
    foreach ($testimonials as $tm) {
        $r = $tm->report;
        $school = $r->nama_sekolah ?? 'NULL';
        echo implode(' | ', ["id:{$tm->id}", "approved:" . ($tm->is_approved? '1':'0'), "student:{$tm->student_name}", "report_id:{$tm->report_id}", "report_school:{$school}"]) . PHP_EOL;
    }
}
