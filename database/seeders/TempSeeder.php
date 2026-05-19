<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Startup;

class TempSeeder extends Seeder
{
    public function run()
    {
        $u = User::firstOrCreate(
            ['email' => 'founder@test.com'],
            ['name' => 'Test Founder', 'password' => bcrypt('password'), 'role' => 'founder']
        );

        if (Startup::count() === 0) {
            Startup::create([
                'title' => 'AI NextGen',
                'short_description' => 'Revolutionizing AI.',
                'description' => "AI NextGen is building the future of artificial intelligence.\n\nWe provide cutting-edge infrastructure and models for enterprise usage, scaling down inference costs by 10x.\n\nOur team comprises industry veterans from top tech companies.",
                'funding_goal' => 1000000,
                'current_funding' => 250000,
                'category' => 'Artificial Intelligence',
                'startup_stage' => 'Seed',
                'deadline' => now()->addDays(30),
                'status' => 'active',
                'founder_id' => $u->id
            ]);
        }
    }
}
