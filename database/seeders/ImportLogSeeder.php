<?php

namespace Database\Seeders;

use App\Models\ImportLog;
use App\Models\User;
use Illuminate\Database\Seeder;

class ImportLogSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $adminUsers = User::whereIn('role', ['owner', 'admin'])->get();

        // Create successful imports
        ImportLog::factory(5)->successful()->create([
            'user_id' => $adminUsers->random()->id,
        ]);

        // Create imports with errors
        ImportLog::factory(3)->withErrors()->create([
            'user_id' => $adminUsers->random()->id,
        ]);

        // Create regular import logs
        ImportLog::factory(7)->create([
            'user_id' => $adminUsers->random()->id,
        ]);
    }
}