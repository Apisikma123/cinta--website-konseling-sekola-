<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\School;

class SchoolSeeder extends Seeder
{
    public function run()
    {
        School::firstOrCreate(
            ['name' => 'Test School'],
            [
                'city' => 'Jakarta',
                'is_active' => true,
                'secret_code_generated_at' => now(),
            ]
        );
        
        $this->command->info("✓ Test School created or already exists");
    }
}
