<?php

namespace Database\Factories;

use App\Models\Pet;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Donation>
 */
class DonationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'pet_id' => Pet::factory(),
            'donor_name' => $this->faker->name,
            'amount' => $this->faker->randomFloat(2, 10, 500),
            'payment_method' => $this->faker->randomElement(['yape', 'plin', 'bcp']),
            'proof_path' => 'donations/proof_placeholder.jpg',
            'status' => $this->faker->randomElement(['Pendiente', 'Verificado', 'Rechazado']),
            'admin_note' => $this->faker->optional()->sentence,
        ];
    }
}
