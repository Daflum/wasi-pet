<?php

namespace Database\Seeders;

use App\Models\AdoptionRequest;
use App\Models\Pet;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PetSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Pets that are 'Adoptado' -> Must have 1 Approved Request
        Pet::factory(10)
            ->create(['status' => 'Adoptado'])
            ->each(function ($pet) {
                AdoptionRequest::withoutEvents(function () use ($pet) {
                    AdoptionRequest::factory()->create([
                        'pet_id' => $pet->id,
                        'status' => 'Aprobado'
                    ]);

                    // Optional: closed requests (history)
                    AdoptionRequest::factory(rand(0, 3))->create([
                        'pet_id' => $pet->id,
                        'status' => 'Cerrada'
                    ]);
                });
            });

        // 2. Pets that are 'En Proceso' -> Must have Pendiente Requests
        Pet::factory(10)
            ->create(['status' => 'En Proceso'])
            ->each(function ($pet) {
                AdoptionRequest::withoutEvents(function () use ($pet) {
                     AdoptionRequest::factory(rand(1, 3))->create([
                        'pet_id' => $pet->id,
                        'status' => 'Pendiente'
                    ]);
                });
            });

        // 3. Pets that are 'Disponible' -> No active requests (maybe rejected/closed)
        Pet::factory(10)
            ->create(['status' => 'Disponible'])
            ->each(function ($pet) {
                AdoptionRequest::withoutEvents(function () use ($pet) {
                    // Occasionally create some rejected/closed requests
                    if (rand(0, 3)) {
                        AdoptionRequest::factory(rand(1, 2))->create([
                            'pet_id' => $pet->id,
                            'status' => 'Rechazado'
                        ]);
                    }
                });
            });
    }
}
