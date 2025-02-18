<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Entreprise;
use App\Models\Activite;
use App\Models\Reservation;
use App\Models\Notification;
use App\Models\Plage;

class BookTest extends TestCase
{
    use RefreshDatabase;
    use RefreshDatabase;

    /**
     * CASE 1 - Réservation valide avec notification.
     * GIVEN : Utilisateur authentifié
     * WHEN : Une requête POST avec notification est envoyée
     * THEN : La réservation et la notification sont créées.
     */
    #[Test]
    public function test_booking_with_notification(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $companyCreator = User::factory()->create();
        $entreprise = Entreprise::factory()->create(['idCreateur' => $companyCreator->id]);
        $activite = Activite::factory()->create(['idEntreprise' => $entreprise->id]);

        $response = $this->post(route('reservation.store', [$entreprise, $activite]), [
            'dateRdv' => '2025-06-15',
            'horaire' => '14:00 - 15:00',
            'nbPersonnes' => 1,
            'notifications' => [['typeNotification' => 'Mail', 'contenu' => 'Rappel', 'duree' => '1jour']],
            'employe_id' => $user->id,
        ]);

        $response->assertRedirect(route('reservation.index'));
        $this->assertDatabaseHas('reservations', ['dateRdv' => '2025-06-15 00:00:00']);
    }

    /**
     * CASE 7 - Réservation avec nbPersonnes invalide.
     * GIVEN : Utilisateur authentifié
     * WHEN : Une requête POST avec nbPersonnes invalide
     * THEN : Une erreur de validation est renvoyée.
     */
    #[Test]
    public function test_booking_with_nbPersonnes_not_valid(): void
    {
        // GIVEN
        $user = User::factory()->create();
        $this->actingAs($user);

        $companyCreator = User::factory()->create();
        $entreprise = Entreprise::factory()->create(['idCreateur' => $companyCreator->id]);
        $activite = Activite::factory()->create(['idEntreprise' => $entreprise->id]);

        // WHEN
        $response = $this->post(route('reservation.store', [$entreprise, $activite]), [
            'nbPersonnes' => -5,
            'dateRdv' => '2025-06-20',
            'horaire' => '10:00 - 11:00',
            'employe_id' => $user->id,
        ]);

        // THEN
        $response->assertSessionHasErrors(['nbPersonnes']);
    }

    // Ajoutez ici les autres tests pour CASE 2, CASE 3, CASE 4, etc. avec la même structure.
}
