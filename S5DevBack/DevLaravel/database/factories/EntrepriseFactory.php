<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class EntrepriseFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        return [
            'libelle' => fake()->company,
            'description' => fake()->sentences(4, true),
            'adresse' => fake()->sentences(1, true),
            'metier' => fake()->word,
            'numTel' => fake()->numberBetween(1, 10),
            'type' => fake()->numberBetween(1, 3),
            'email' => fake()->unique()->safeEmail(),
            'cheminImg' => json_encode(['https://static.thenounproject.com/png/1584264-200.png']),
            'publier' => fake()->numberBetween(0, 1)
        ];
    }
}