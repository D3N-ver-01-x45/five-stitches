<?php

namespace Database\Seeders;

use App\Enums\OrderStatus;
use App\Enums\UserRole;
use App\Models\Order;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * LOCAL DEVELOPMENT ONLY. The demo accounts below have a public password,
     * so never run this seeder on the live site.
     */
    public function run(): void
    {
        foreach ([
            ['admin@fivestitches.test', 'Five Stitches Admin', UserRole::Admin],
            ['seller@fivestitches.test', 'Five Stitches Seller', UserRole::Seller],
            ['buyer@fivestitches.test', 'Demo Buyer', UserRole::Buyer],
        ] as [$email, $name, $role]) {
            $user = User::firstOrCreate(
                ['email' => $email],
                ['name' => $name, 'password' => Hash::make('password')],
            );
            // role and email_verified_at are not mass-assignable, so set them directly.
            $user->forceFill(['role' => $role, 'email_verified_at' => $user->email_verified_at ?? now()])->save();
        }

        if (Order::count() === 0) {
            Order::factory()->count(6)->status(OrderStatus::Shipped)->create(['created_at' => now()->subDays(12)]);
            Order::factory()->count(5)->status(OrderStatus::BeingCrocheted)->create(['created_at' => now()->subDays(4)]);
            Order::factory()->count(4)->create(['created_at' => now()->subDay()]);
        }
    }
}
