<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
            \App\Models\User::factory()->create([
                'name' => 'Admin',
                'email' => 'admin@example.com',
                'password' => bcrypt('password'),
                'role' => 'admin',
            ]);
                \App\Models\User::factory()->create([
                    'name' => 'Staff',
                    'email' => 'staff@example.com',
                    'password' => bcrypt('password'),
                    'role' => 'staff',
                ]);

    }
}
