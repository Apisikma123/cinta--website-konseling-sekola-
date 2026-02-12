<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class ApproveTeacherSeeder extends Seeder
{
    public function run()
    {
        $teacher = User::where('email', 'guru@sistemcinta.com')->first();
        
        if ($teacher) {
            $teacher->update(['is_approved' => true]);
            $this->command->info("✓ Teacher approved: {$teacher->email}");
        } else {
            $this->command->warn("Teacher not found");
        }
    }
}
