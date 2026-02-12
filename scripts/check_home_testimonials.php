<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Testimonial;
use App\Models\User;
use Illuminate\Support\Facades\Cache;

echo "--- CACHE KEYS ---\n";
$keys = ['home_testimonials'];
// include per-school patterns by listing schools
$schools = App\Models\School::all();
foreach ($schools as $s) {
    $keys[] = 'home_testimonials_school_' . md5($s->name);
    $keys[] = 'home_testimonials_school_like_' . md5($s->name);
}
foreach ($keys as $k) {
    $has = Cache::has($k) ? 'YES' : 'NO';
    echo "$k : $has\n";
}

echo "\n--- RAW DB QUERY (approved testimonials) ---\n";
$testimonials = Testimonial::with('report')->where('is_approved', true)->orderBy('created_at','desc')->limit(3)->get();
echo "Count: " . $testimonials->count() . "\n";
foreach ($testimonials as $t) {
    echo "id={$t->id} report_school=" . ($t->report->nama_sekolah ?? 'NULL') . " content=" . substr($t->content,0,40) . "\n";
}

echo "\n--- AS TEACHER (user id 2) ---\n";
$user = User::find(2);
if ($user) {
    echo "Teacher school: {$user->school}\n";
    // emulate HomeController per-school cache recall
    $schoolModel = App\Models\School::where('name', $user->school)->first();
    if ($schoolModel) echo "Resolved school model name: {$schoolModel->name}\n";
    $filterName = $user->school;
    $tokens = preg_split('/[^a-z0-9]+/i', strtolower($filterName));
    $matches = Testimonial::with('report')->where('is_approved', true)->whereHas('report', function($q) use ($tokens){
        $q->where(function($q2) use ($tokens){
            foreach ($tokens as $tok) {
                $tok = trim($tok);
                if (strlen($tok) < 3) continue;
                $q2->orWhereRaw('LOWER(nama_sekolah) LIKE ?', ["%{$tok}%"]);
            }
        });
    })->orderBy('created_at','desc')->limit(3)->get();
    echo "Matches for teacher filter: " . $matches->count() . "\n";
    foreach ($matches as $m) echo "id={$m->id} report_school=".($m->report->nama_sekolah ?? 'NULL')."\n";
} else {
    echo "No teacher user id=2 found\n";
}
