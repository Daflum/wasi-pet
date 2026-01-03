<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Pet>
 */
class PetFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $type = $this->faker->randomElement(['dog', 'cat']);
        $name = $this->faker->firstName;
        $uniqueId = $this->faker->unique()->numerify('#####');

        return [
            'name' => $name,
            'slug' => Str::slug($name . ' ' . $uniqueId),
            'type' => $type,
            'gender' => $this->faker->randomElement(['Macho', 'Hembra']),
            'birth_date' => $this->faker->dateTimeBetween('-12 years', 'now'),
            'size' => $this->faker->randomElement(['Pequeño', 'Mediano', 'Grande']),
            'description' => $this->faker->paragraph,
            'status' => 'Disponible', // Default status should be 'Disponible' to avoid conflicts with seeder logic
            'image' => 'https://loremflickr.com/600/400/' . $type . '?lock=' . $this->faker->unique()->randomNumber(),
        ];
    }
}
