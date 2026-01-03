<?php

namespace Database\Factories;

use App\Models\Pet;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\AdoptionRequest>
 */
class AdoptionRequestFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'pet_id' => Pet::factory(),
            'name' => $this->faker->name,
            'email' => $this->faker->safeEmail,
            'dni' => $this->faker->numerify('########'),
            'phone' => $this->faker->phoneNumber,
            'address' => $this->faker->address,
            'status' => 'Pendiente',
        ];
    }
}
