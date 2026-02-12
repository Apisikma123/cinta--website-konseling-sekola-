<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Testimonial;
use App\Models\Report;

class TestimonialSeeder extends Seeder
{
    public function run()
    {
        $report = Report::where('tracking_code', 'TEST-CNESJER2')->first();
        
        if ($report) {
            Testimonial::create([
                'report_id' => $report->id,
                'student_name' => 'Murid Test',
                'content' => 'Ini adalah testimoni test untuk menguji sistem approval.',
                'rating' => 5,
                'is_anonymous' => false,
                'is_approved' => false,
            ]);
            
            $this->command->info("✓ Test testimonial created");
        } else {
            $this->command->warn("Report TEST-CNESJER2 not found");
        }
    }
}
