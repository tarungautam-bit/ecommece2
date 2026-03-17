<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::create([
            'name' => 'tarun',
            'email' => 'admin@gmail.com',
            'email_verified_at' => null,
            'password' => Hash::make('password123'),
            'type' => 'admin',
            'total_amount' => 0.00,
        ]);
    }
}