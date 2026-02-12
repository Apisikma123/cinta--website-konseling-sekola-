<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$teacher = App\Models\User::find(2);
if (!$teacher) { echo "No teacher id=2\n"; exit; }
$matches = App\Models\Testimonial::with('report')->orderBy('created_at','desc')->get()->filter(function($t) use ($teacher){
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

if ($matches->isEmpty()) {
    echo "NO_MATCHES\n";
} else {
    foreach ($matches as $m) {
        echo "MATCH: id={$m->id} report_school={$m->report->nama_sekolah}\n";
    }
}
