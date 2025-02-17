<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @file CreneauFactory.php
 * @brief Factory class for generating fake Creneau model data.
 *
 * This factory defines the default state for Creneau model instances,
 * providing random data for database seeding and testing.
 */
class CreneauFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @brief Generates fake data for a Creneau instance.
     * @return array<string, mixed> Associative array of fake Creneau attributes.
     */
    public function definition(): array
    {
        return [
            'dateC' => fake()->date(), ///< Randomly generated date for the slot.
            'heureDeb' => fake()->time('H:i:s'), ///< Random start time.
            'heureFin' => fake()->time('H:i:s') ///< Random end time.
        ];
    }
}
