<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::firstOrCreate(
            ['email' => 'admin@sipps.com'],
            [
                'name' => 'Administrator',
                'password' => Hash::make('password'),
                'google_id' => null,
                'avatar' => null,
                'email_verified_at' => now(),
            ]
        );
    }
}
