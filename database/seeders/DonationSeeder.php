<?php

namespace Database\Seeders;

use App\Models\Donation;
use App\Models\Pet;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DonationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::all();
        $pets = Pet::all();

        if ($users->isEmpty()) {
            $users = User::factory(5)->create();
        }

        if ($pets->isEmpty()) {
            $pets = Pet::factory(5)->create();
        }

        Donation::factory(20)->create([
            'user_id' => $users->random()->id,
            'pet_id' => $pets->random()->id,
        ]);
    }
}
