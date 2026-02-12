<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Testimonial;

$testimonials = Testimonial::with('report')->where('is_approved', true)->get();
if ($testimonials->isEmpty()) {
    echo "NO_APPROVED_TESTIMONIALS\n";
    exit(0);
}

foreach ($testimonials as $t) {
    $report = $t->report;
    $school = $report->nama_sekolah ?? 'NO_SCHOOL';
    echo implode('|', [
        $t->id,
        $t->student_name,
        $t->is_anonymous ? '1' : '0',
        $t->rating ?? 'null',
        $school,
        substr($t->content, 0, 50)
    ]) . PHP_EOL;
}
