<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Entreprise;
use App\Models\Reservation;
use App\Models\Creneau;
use App\Models\Notification;
use App\Models\Plage;
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

        Reservation::factory(8)->create();

        Creneau::factory(2)->create();

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
    }
}
