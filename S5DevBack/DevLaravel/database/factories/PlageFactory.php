<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Notification>
 */
class PlageFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'datePlage' => fake()->date(),
            'heureDeb' => fake()->time('H:i:s'),
            'heureFin'=> fake()->time('H:i:s'),
            'interval' => fake()->time('H:i:s'),
            'planTables' => json_encode(['Test']),
            'entreprise_id' => fake()->numberBetween(1, 3),
        ];
    }
}
