<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ReservationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        return [
            'dateRdv' => fake()->date(),
            'heureDeb' => fake()->time('H:i:s'),
            'heureFin' => fake()->time('H:i:s'), 
            'nbPersonnes' => fake()->numberBetween(1, 5),
        ];
    }
}
