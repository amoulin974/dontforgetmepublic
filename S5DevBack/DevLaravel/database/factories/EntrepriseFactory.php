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
            'nom' => $this->faker->words(1, true),
            'prenom' => $this->faker->words(1, true),
            'libelle' => $this->faker->company,
            'description' => $this->faker->sentences(4, true),
            'adresse' => $this->faker->sentences(1, true),
            'metier' => $this->faker->word,
            'numTel' => $this->faker->numberBetween(1, 10),
            'type' => $this->faker->numberBetween(1, 3),
            'publier' => $this->faker->numberBetween(0, 1),
            'email' => fake()->unique()->safeEmail(),
        ];
    }
}