<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Entreprise;
use App\Models\Reservation;
use App\Models\Creneau;
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

        Entreprise::factory(2)->create();

        Reservation::factory(2)->create();

        Creneau::factory(2)->create();
    }
}
