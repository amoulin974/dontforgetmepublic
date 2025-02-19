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
     * CASE 1 - Booking valid with notification.
     * GIVEN : User connected
     * WHEN : A booking attempt is made
     * THEN : The booking and the notifications are created
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
     * CASE 2 - Booking valid without notification.
     * GIVEN : User connected
     * WHEN : A booking attempt is made
     * THEN : The booking is created
     */
    #[Test]
    public function test_booking_without_notification(): void
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
            'employe_id' => $companyCreator->id,
        ]);

        $response->assertRedirect(route('reservation.index'));
        $this->assertDatabaseHas('reservations', ['dateRdv' => '2025-06-15 00:00:00']);
    }

    /**
     * CASE 3 - Booking valid with employee affected and without notification.
     * GIVEN : User connected
     * WHEN : A booking attempt is made
     * THEN : The booking is created with the employee affected
     */
    #[Test]
    public function test_booking_with_employee_affected_and_without_notification(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $companyCreator = User::factory()->create();
        $entreprise = Entreprise::factory()->create(['idCreateur' => $companyCreator->id]);
        $activite = Activite::factory()->create(['idEntreprise' => $entreprise->id]);
        $employee = User::factory()->create();
        $employee->travailler_entreprises()->attach($entreprise->id, ['idActivite' => $activite->id, 'statut' => 'Employé']);

        $response = $this->post(route('reservation.store', [$entreprise, $activite]), [
            'dateRdv' => '2025-06-15',
            'horaire' => '14:00 - 15:00',
            'nbPersonnes' => 1,
            'employe_id' => $employee->id,
        ]);

        $response->assertRedirect(route('reservation.index'));
        $this->assertDatabaseHas('reservations', ['dateRdv' => '2025-06-15 00:00:00']);
    }

    /**
     * CASE 4 - Booking valid with employee affected and with notification.
     * GIVEN : User connected
     * WHEN : A booking attempt is made
     * THEN : The booking is created with the employee affected and a notification created
     */
    #[Test]
    public function test_booking_with_employee_affected_and_with_notification(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $companyCreator = User::factory()->create();
        $entreprise = Entreprise::factory()->create(['idCreateur' => $companyCreator->id]);
        $activite = Activite::factory()->create(['idEntreprise' => $entreprise->id]);
        $employee = User::factory()->create();
        $employee->travailler_entreprises()->attach($entreprise->id, ['idActivite' => $activite->id, 'statut' => 'Employé']);

        $response = $this->post(route('reservation.store', [$entreprise, $activite]), [
            'dateRdv' => '2025-06-15',
            'horaire' => '14:00 - 15:00',
            'nbPersonnes' => 1,
            'notifications' => [['typeNotification' => 'Mail', 'contenu' => 'Rappel', 'duree' => '1jour']],
            'employe_id' => $employee->id,
        ]);

        $response->assertRedirect(route('reservation.index'));
        $this->assertDatabaseHas('reservations', ['dateRdv' => '2025-06-15 00:00:00']);
    }

    /**
     * CASE 5 - Booking for many people valid with employee affected and with notification.
     * GIVEN : User connected
     * WHEN : A booking attempt is made
     * THEN : The booking is created with the employee affected and a notification created
     */
    #[Test]
    public function test_booking_for_many_people_with_employee_affected_and_with_notification(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $companyCreator = User::factory()->create();
        $entreprise = Entreprise::factory()->create(['idCreateur' => $companyCreator->id]);
        $activite = Activite::factory()->create(['idEntreprise' => $entreprise->id]);
        $employee = User::factory()->create();
        $employee->travailler_entreprises()->attach($entreprise->id, ['idActivite' => $activite->id, 'statut' => 'Employé']);

        $response = $this->post(route('reservation.store', [$entreprise, $activite]), [
            'dateRdv' => '2025-06-15',
            'horaire' => '14:00 - 15:00',
            'nbPersonnes' => 5,
            'notifications' => [['typeNotification' => 'Mail', 'contenu' => 'Rappel', 'duree' => '1jour']],
            'employe_id' => $employee->id,
        ]);

        $response->assertRedirect(route('reservation.index'));
        $this->assertDatabaseHas('reservations', ['dateRdv' => '2025-06-15 00:00:00']);
    }

    /**
     * CASE 6 - Booking for many people valid with employee affected and without notification.
     * GIVEN : User connected
     * WHEN : A booking attempt is made
     * THEN : The booking is created with the employee affected
     */
    #[Test]
    public function test_booking_for_many_people_with_employee_affected_and_without_notification(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $companyCreator = User::factory()->create();
        $entreprise = Entreprise::factory()->create(['idCreateur' => $companyCreator->id]);
        $activite = Activite::factory()->create(['idEntreprise' => $entreprise->id]);
        $employee = User::factory()->create();
        $employee->travailler_entreprises()->attach($entreprise->id, ['idActivite' => $activite->id, 'statut' => 'Employé']);

        $response = $this->post(route('reservation.store', [$entreprise, $activite]), [
            'dateRdv' => '2025-06-15',
            'horaire' => '14:00 - 15:00',
            'nbPersonnes' => 5,
            'employe_id' => $employee->id,
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
            'employe_id' => $companyCreator->id,
        ]);

        // THEN
        $response->assertSessionHasErrors(['nbPersonnes']);
    }

    /**
     * CASE 8 - Booking deleting.
     * GIVEN : User connected
     * WHEN : A booking deleting is made
     * THEN : The booking is deleted
     */
    #[Test]
    public function test_booking_deleting(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $reservation = Reservation::factory()->create([
            'dateRdv' => now()->format('Y-m-d'),
            'heureDeb' => '09:00:00',
            'heureFin' => '10:00:00',
            'nbPersonnes' => 2,
        ]);

        $response = $this->delete(route('reservation.destroy', ['reservation' => $reservation->id]));

        $response->assertRedirect(route('reservation.index'));
    }
}
