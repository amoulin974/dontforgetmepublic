<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Notification>
 */
class NotificationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'categorie' => fake()->word(1),
            'delai' => fake()->time('H:i:s'),
            'etat' => fake()->numberBetween(0,1),
            'contenu' => fake()->sentence(1)
        ];
    }
}
