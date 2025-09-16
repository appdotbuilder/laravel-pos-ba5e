<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create default owner
        User::create([
            'name' => 'Owner POS',
            'email' => 'owner@pos.com',
            'password' => Hash::make('password'),
            'role' => 'owner',
            'is_active' => true,
            'email_verified_at' => now(),
        ]);

        // Create default admin
        User::create([
            'name' => 'Admin POS',
            'email' => 'admin@pos.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'is_active' => true,
            'email_verified_at' => now(),
        ]);

        // Create default kasir
        User::create([
            'name' => 'Kasir POS',
            'email' => 'kasir@pos.com',
            'password' => Hash::make('password'),
            'role' => 'kasir',
            'is_active' => true,
            'email_verified_at' => now(),
        ]);

        // Create additional random users
        User::factory(5)->owner()->create();
        User::factory(8)->admin()->create();
        User::factory(12)->kasir()->create();
        User::factory(3)->inactive()->create();
    }
}