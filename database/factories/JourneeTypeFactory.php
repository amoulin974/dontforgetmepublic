<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @file JourneeTypeFactory.php
 * @brief Factory class for generating fake JourneeType model data.
 *
 * Provides random values for the attributes of the JourneeType model,
 * used for seeding and testing.
 */
class JourneeTypeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @brief Returns random values for creating a fake JourneeType instance.
     * @return array<string, mixed> An associative array of fake attributes.
     */
    public function definition(): array
    {
        return [
            'libelle' => fake()->string(), ///< Random string for 'libelle'.
            'planning' => json_encode(['Test']) ///< JSON-encoded planning data.
        ];
    }
}
