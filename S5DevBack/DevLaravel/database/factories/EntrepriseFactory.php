<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @file EntrepriseFactory.php
 * @brief Factory class for generating fake Entreprise model data.
 *
 * Provides a set of random values for the attributes of the Entreprise model,
 * useful for seeding and testing the application.
 */
class EntrepriseFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @brief Returns random values for creating a fake Entreprise instance.
     * @return array<string, mixed> An associative array of fake attributes.
     */
    public function definition(): array
    {
        return [
            'libelle' => fake()->company, ///< Company name.
            'siren' => fake()->numerify('######### #####'), ///< SIREN number.
            'description' => fake()->sentences(4, true), ///< Company description.
            'adresse' => fake()->sentences(1, true), ///< Address.
            'metier' => fake()->word, ///< Job or business type.
            'numTel' => fake()->unique()->numerify('+33 # ## ## ## ##'), ///< Phone number.
            'type' => fake()->numberBetween(1, 3), ///< Type of company (1 to 3).
            'email' => fake()->unique()->companyEmail(), ///< Email address.
            'cheminImg' => json_encode(['https://static.thenounproject.com/png/1584264-200.png']), ///< Image path (JSON encoded).
            'publier' => fake()->numberBetween(0, 1), ///< Whether to publish the company (0 or 1).
            'typeRdv' => json_encode(['Test']), ///< Appointment type (JSON encoded).
            'idCreateur' => fake()->numberBetween(1, 2), ///< Creator ID (1 or 2).
        ];
    }
}
