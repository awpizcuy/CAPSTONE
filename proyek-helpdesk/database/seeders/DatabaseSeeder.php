<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        User::create([
            'name' => 'Kepala IT',
            'email' => 'kepalait@example.com',
            'password' => Hash::make('password'),
            'role' => 'kepala_it',
        ]);

        User::create([
            'name' => 'Teknisi Default',
            'email' => 'teknisi@example.com',
            'password' => Hash::make('password'),
            'role' => 'teknisi',
        ]);
    }
}
