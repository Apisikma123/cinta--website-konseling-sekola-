<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Carbon\Carbon;

class AdminSeeder extends Seeder
{
    public function run()
    {
        // Admin user
        $admin = User::firstOrCreate(
            ['email' => 'admin@sistemcinta.com'],
            [
                'name' => 'Admin CINTA',
                'password' => Hash::make('Admin@123'),
                'role' => 'admin',
                'is_approved' => true,
                'email_verified_at' => Carbon::now(),
            ]
        );

        if ($admin->wasRecentlyCreated) {
            $this->command->info("✓ Admin created: admin@sistemcinta.com / Admin@123");
        } else {
            $this->command->info("✓ Admin already exists");
        }

        // Test teacher user
        $teacher = User::firstOrCreate(
            ['email' => 'guru@sistemcinta.com'],
            [
                'name' => 'Guru Test',
                'school' => 'Test School',
                'password' => Hash::make('Guru@123'),
                'role' => 'teacher',
                'is_approved' => true,
                'email_verified_at' => Carbon::now(),
            ]
        );

        if ($teacher->wasRecentlyCreated) {
            $this->command->info("✓ Teacher created: guru@sistemcinta.com / Guru@123");
        } else {
            $this->command->info("✓ Teacher already exists");
        }
    }
}
