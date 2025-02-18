<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @file NotificationFactory.php
 * @brief Factory class for generating fake Notification model data.
 *
 * Defines random values for the attributes of the Notification model,
 * useful for seeding and testing purposes.
 */
class NotificationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @brief Returns random values for creating a fake Notification instance.
     * @return array<string, mixed> Associative array of fake Notification attributes.
     */
    public function definition(): array
    {
        return [
            'categorie' => fake()->word(), ///< Random category word.
            'delai' => fake()->randomElement([24, 48, 168]), ///< Random delay (24, 48, or 168 hours).
            'etat' => fake()->numberBetween(0, 1), ///< Random state (0 or 1).
            'contenu' => fake()->sentence(), ///< Random sentence for content.
            'reservation_id' => fake()->numberBetween(1, 5), ///< Random reservation ID between 1 and 5.
        ];
    }
}
