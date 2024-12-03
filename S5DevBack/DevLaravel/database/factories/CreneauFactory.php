<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Creneau>
 */
class CreneauFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'dateC' => fake()->date(),
            'heureDeb' => fake()->time('H:i:s'),
            'heureFin' => fake()->time('H:i:s')
        ];
    }
}
