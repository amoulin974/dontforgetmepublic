<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Activite;

/**
 * @file ActiviteFactory.php
 * @brief Factory for generating dummy data for the Activite model.
 *
 * This factory is used to generate fake data for testing and seeding
 * the "activites" table in the database.
 */
class ActiviteFactory extends Factory
{
    /**
     * @var string The model that this factory is associated with.
     */
    protected $model = Activite::class;

    /**
     * @brief Define the model's default state.
     *
     * This method generates fake values for the Activite model fields,
     * which can be used in database seeds or tests.
     *
     * @return array<string, mixed> An associative array representing a fake Activite instance.
     */
    public function definition(): array
    {
        return [
            'libelle' => fake()->sentence(3, true), ///< Generates a random activity label.
            'duree' => fake()->time('H:i:s'), ///< Generates a random duration in HH:MM:SS format.
            'idEntreprise' => fake()->numberBetween(1, 3), ///< Assigns a random company ID between 1 and 3.
        ];
    }
}
