<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     *
     * LOCAL DEVELOPMENT ONLY. The demo account below has a public password,
     * so never run this seeder on the live site.
     */
    public function run(): void
    {
        User::firstOrCreate(
            ['email' => 'admin@fivestitches.test'],
            [
                'name' => 'Five Stitches Admin',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ],
        );
    }
}
