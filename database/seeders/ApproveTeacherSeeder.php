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
            // Update both fields for compatibility and new approval system
            $teacher->update([
                'is_approved' => true,
                'approval_status' => 'approved',
                'otp_verified' => true,
            ]);
            $this->command->info("✓ Teacher approved: {$teacher->email}");
        } else {
            $this->command->warn("Teacher not found");
        }
    }
}
