<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Report;
use App\Models\School;
use Illuminate\Support\Str;

class TestReportSeeder extends Seeder
{
    public function run()
    {
        $school = School::first();
        
        if (!$school) {
            $this->command->warn("No school found. Creating test school...");
            $school = School::create([
                'name' => 'SMA Test',
                'city' => 'Jakarta',
                'secret_code_generated_at' => now(),
            ]);
        }

        // Create a test report with status 'selesai'
        $report = Report::create([
            'tracking_code' => 'TEST-' . Str::upper(Str::random(8)),
            'nama_murid' => 'Murid Test',
            'email_murid' => 'murid@test.com',
            'nama_sekolah' => $school->name,
            'kelas' => '12 IPA 1',
            'title' => 'Test Laporan',
            'jenis_laporan' => 'akademik',
            'isi_laporan' => 'Ini adalah laporan test untuk mengecek modal testimoni.',
            'status' => 'selesai',  // Set status to selesai
        ]);

        $this->command->info("✓ Test report created: {$report->tracking_code}");
        $this->command->info("✓ Visit: http://localhost/result/{$report->tracking_code}");
    }
}
