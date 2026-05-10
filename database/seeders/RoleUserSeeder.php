<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoleUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\User::updateOrCreate(
            ['email' => 'owner@sipps.com'],
            [
                'name' => 'Owner SIPPS',
                'password' => \Illuminate\Support\Facades\Hash::make('password'),
                'role' => 'owner',
                'email_verified_at' => now(),
            ]
        );

        \App\Models\User::updateOrCreate(
            ['email' => 'finance@sipps.com'],
            [
                'name' => 'Finance SIPPS',
                'password' => \Illuminate\Support\Facades\Hash::make('password'),
                'role' => 'keuangan',
                'email_verified_at' => now(),
            ]
        );
    }
}
