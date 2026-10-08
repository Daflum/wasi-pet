<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\App;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        if (! App::environment('local', 'development')) {
            return;
        }

        User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@wasipet.pe',
            'role' => 'admin',
        ]);

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        $this->call([
            PetSeeder::class,
            PaymentMethodSeeder::class,
            DonationSeeder::class,
        ]);
    }
}
