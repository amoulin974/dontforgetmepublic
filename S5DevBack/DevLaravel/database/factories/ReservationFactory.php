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
            'nom' => $this->faker->words(1, true),
            'prenom' => $this->faker->words(1, true),
            'manufacturer' => $this->faker->company,
            'description' => $this->faker->sentences(4, true),
            'mainPepper' => $this->faker->word,
            'imageUrl' => $this->faker->imageUrl(),
            'heat' => $this->faker->numberBetween(1, 10),
        ];
    }
}
