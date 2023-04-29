<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Database\Factories\UserFactory;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Default User Account
        UserFactory::new()->create([
            'first_name' => 'Default',
            'last_name' => 'User',
            'email' => 'default@app.dev',
            'password' => Hash::make('password'),
        ]);

        // bulk users
        UserFactory::new()->count(20)->create([]);
    }
}
