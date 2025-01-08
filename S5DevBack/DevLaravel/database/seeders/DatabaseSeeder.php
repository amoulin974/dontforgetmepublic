<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Entreprise;
use App\Models\Reservation;
use App\Models\Creneau;
use App\Models\Notification;
use App\Models\Plage;
use App\Models\Activite;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'nom' => 'Test',
            'prenom' => 'User',
            'email' => 'test@example.com',
            'numTel' => '0000000000',
            'password' => 'abcd',
            'typeNotif' => 'test',
            'delaiAvantNotif' => '2000-01-01T00:00:00Z',
            'superadmin' => 1,
        ]);

        Entreprise::factory(3)->create();

        Entreprise::factory()->create([
            'libelle' => fake()->company,
            'siren' => fake()->numerify('######### #####'),
            'adresse' => fake()->address(),
            'metier' => fake()->city(),
            'description' => fake()->state(),
            'type' => fake()->postcode(),
            'numTel' => fake()->phoneNumber(),
            'email' => fake()->unique()->companyEmail(),
            'cheminImg' => json_encode(['https://www.map24.com/wp-content/uploads/2021/11/6784174_s.jpg']),
            'publier' => 1,
        ]);

        Reservation::factory(10)->create();

        Creneau::factory(2)->create();

        Creneau::factory()->create([
            'dateC' => '2024-12-19T00:00:00Z',
            'heureDeb' => fake()->time('H:i:s'),
            'heureFin' => fake()->time('H:i:s'),
        ]);

        Notification::factory(10)->create();

        Reservation::factory()->create([
            'dateRdv' => '2000-01-01T00:00:00Z',
            'heureDeb' => fake()->time('H:i:s'),
            'heureFin' => fake()->time('H:i:s'),
            'nbPersonnes' => 3,
        ]);

        Notification::factory()->create([
            'categorie' => 'UneCatNotif',
            'delai' => fake()->time('H:i:s'),
            'etat' => 0,
            'contenu' => 'UnContenuTest',
            'reservation_id' => 1,
        ]);

        Plage::factory(10)->create();

        Activite::factory(10)->create();

        // Pour les tests
        User::factory()->create([
            'nom' => 'test',
            'prenom' => 'test',
            'email' => 'test@test.test',
            'numTel' => '01234567',
            'password' => 'testtest',
            'typeNotif' => 'test',
            'delaiAvantNotif' => '2000-01-01T00:00:00Z',
            'superadmin' => 1,
        ]);
    }
}
