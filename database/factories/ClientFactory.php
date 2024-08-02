<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Client>
 */
class ClientFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name'         => fake()->name(),
            'address'      => fake()->address(),
            'phone_number' => '0'.(fake()->numberBetween(90000000, 99999999)),
            'notes'        => 'Acá escribís notas sobre el cliente. '.fake()->sentence(5),
            'rating'       => fake()->numberBetween(1, 10),
        ];
    }
}
