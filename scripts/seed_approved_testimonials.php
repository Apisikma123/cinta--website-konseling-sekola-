<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\School;
use App\Models\Report;
use App\Models\Testimonial;
use Illuminate\Support\Str;

$school = School::first();
if (!$school) {
    echo "No school found, creating one...\n";
    $school = School::create(["name" => "SMA Test Auto", "city" => "Jakarta"]);
}

// Ensure there are at least 3 reports for that school
$reports = Report::where('nama_sekolah', $school->name)->take(3)->get();
if ($reports->count() < 3) {
    for ($i = $reports->count(); $i < 3; $i++) {
        $rep = Report::create([
            'tracking_code' => 'AUTO' . Str::upper(Str::random(6)),
            'nama_murid' => 'Murid Auto ' . ($i+1),
            'email_murid' => null,
            'nama_sekolah' => $school->name,
            'kelas' => 'X IPA',
            'title' => 'Laporan Auto ' . ($i+1),
            'jenis_laporan' => 'lainnya',
            'isi_laporan' => 'Laporan otomatis untuk pengujian testimonial.',
            'status' => 'selesai',
        ]);
        $reports->push($rep);
    }
}

// Create 3 approved testimonials linked to those reports
foreach ($reports as $i => $report) {
    Testimonial::create([
        'report_id' => $report->id,
        'user_id' => null,
        'student_name' => 'Auto Tester ' . ($i+1),
        'content' => 'Ini testimoni otomatis #' . ($i+1) . ' — sistem bekerja dengan baik.',
        'rating' => 4,
        'is_anonymous' => false,
        'is_approved' => true,
    ]);
}

echo "Created/ensured 3 approved testimonials for school: {$school->name}\n";
