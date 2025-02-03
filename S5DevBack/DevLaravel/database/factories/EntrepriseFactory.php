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
            'siren' => fake()->numerify('######### #####'),
            'description' => fake()->sentences(4, true),
            'adresse' => fake()->sentences(1, true),
            'metier' => fake()->word,
            'numTel' => fake()->unique()->numerify('+33 # ## ## ## ##'),
            'email' => fake()->unique()->companyEmail(),
            'cheminImg' => json_encode(['https://static.thenounproject.com/png/1584264-200.png']),
            'publier' => fake()->numberBetween(0, 1),
            'typeRdv' => json_encode(['Test']),
            'idCreateur' => fake()->numberBetween(1, 2),
        ];
    }
}