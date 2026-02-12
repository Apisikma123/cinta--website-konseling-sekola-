<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$teacher = App\Models\User::find(2);
if (!$teacher) { echo "NO_TEACHER\n"; exit; }

$testimonials = App\Models\Testimonial::with('report')->orderBy('created_at','desc')->get()->filter(function ($t) use ($teacher) {
    if (!$t->report) return false;
    if (empty($teacher->school)) return false;
    $reportSchool = strtolower($t->report->nama_sekolah ?? '');
    $teacherSchool = strtolower($teacher->school);
    if (str_contains($reportSchool, $teacherSchool) || str_contains($teacherSchool, $reportSchool)) return true;
    $toksTeacher = preg_split('/[^a-z0-9]+/i', $teacherSchool);
    foreach ($toksTeacher as $tok) {
        $tok = trim($tok);
        if (strlen($tok) < 3) continue;
        if (str_contains($reportSchool, $tok)) return true;
    }
    $toksReport = preg_split('/[^a-z0-9]+/i', $reportSchool);
    foreach ($toksReport as $tok) {
        $tok = trim($tok);
        if (strlen($tok) < 3) continue;
        if (str_contains($teacherSchool, $tok)) return true;
    }
    return false;
});

$pending = $testimonials->where('is_approved', false);
$approved = $testimonials->where('is_approved', true);

echo "Teacher: {$teacher->name} (school: {$teacher->school})\n";
echo "Matched testimonials: " . $testimonials->count() . "\n";
echo "Pending: " . $pending->count() . "\n";
echo "Approved: " . $approved->count() . "\n";

if ($approved->count()) {
    echo "Approved IDs: " . implode(',', $approved->pluck('id')->toArray()) . "\n";
}

foreach ($testimonials as $t) {
    echo "- id={$t->id} approved=".($t->is_approved?1:0)." report_school=".($t->report->nama_sekolah ?? 'NULL')." content=".substr($t->content,0,40)."\n";
}
