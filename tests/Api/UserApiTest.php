<?php

namespace Tests\Api;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Notification;
use App\Models\Reservation;

class UserApiTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
    }

    /**
     * CASE 1 - Retrieve detailed notifications
     * GIVEN : A user is authenticated and valid notifications exist
     * WHEN : A request is made to retrieve notifications
     * THEN : Notification details should be returned in the correct structure
     */
    public function test_get_details_should_return_notification_data(): void
    {
        // GIVEN : A user is authenticated and valid notifications exist
        $response = $this->actingAs($this->user)->get('/api/details');

        // WHEN : A request is made to retrieve notifications

        // THEN : Notification details should be returned in the correct structure
        $response->assertStatus(200);
        $response->assertJsonStructure([
            '*' => [
                'userNom',
                'userPrenom',
                'userNumTel',
                'userEmail',
                'entrepriseNom',
                'dateRendezVous',
                'heureRendezVous',
                'notifId',
                'notifCategorie',
                'notifEtat',
                'notifDelaiAvantNotif',
            ]
        ]);
    }

    /**
     * CASE 2 - Update notification state
     * GIVEN : A user is authenticated and a valid notification exists
     * WHEN : A request is made to update the notification state
     * THEN : The notification state should be updated successfully
     */
    public function test_update_notification_state_should_return_success(): void
    {
        // GIVEN : A user is authenticated and a valid notification exists
        $reservation = Reservation::factory()->create();
        $notification = Notification::factory()->create([
            'reservation_id' => $reservation->id,
            'etat' => false,
        ]);

        // WHEN : A request is made to update the notification state
        $response = $this->actingAs($this->user)->patch('/api/details/' . $notification->id, ['etat' => true]);

        // THEN : The notification state should be updated successfully
        $response->assertStatus(200);
        $response->assertJson(['message' => 'Notification updated successfully.']);
        $this->assertDatabaseHas('notifications', ['id' => $notification->id, 'etat' => true]);
    }

    /**
     * CASE 3 - Invalid update notification state (invalid data)
     * GIVEN : A user is authenticated and a valid notification exists
     * WHEN : A request is made to update the notification state with invalid data
     * THEN : A validation error should be returned
     */
    public function test_update_notification_state_with_invalid_data_should_return_error(): void
    {
        // GIVEN : A user is authenticated and a valid notification exists
        $reservation = Reservation::factory()->create();
        $notification = Notification::factory()->create([
            'reservation_id' => $reservation->id,
        ]);

        // WHEN : A request is made to update the notification state with invalid data
        $response = $this->actingAs($this->user)
            ->patch('/api/details/' . $notification->id, ['etat' => 'invalid'], ['Accept' => 'application/json']);

        // THEN : A validation error should be returned
        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['etat']);
    }

    /**
     * CASE 4 - Delete notification and associated reservation details
     * GIVEN : A user is authenticated and a valid notification exists
     * WHEN : A request is made to delete the notification
     * THEN : The notification and associated reservation details should be deleted successfully
     */
    public function test_delete_notification_and_associated_data_should_return_success(): void
    {
        // GIVEN : A user is authenticated and a valid notification exists
        $reservation = Reservation::factory()->create();
        $notification = Notification::factory()->create([
            'reservation_id' => $reservation->id,
        ]);

        // WHEN : A request is made to delete the notification
        $response = $this->actingAs($this->user)->delete('/api/details/' . $notification->id);

        // THEN : The notification and associated reservation details should be deleted successfully
        $response->assertStatus(200);
        $response->assertJson(['message' => 'Notification and associated reservation details deleted successfully.']);
        $this->assertDatabaseMissing('notifications', ['id' => $notification->id]);
    }

    /**
     * CASE 5 - Delete non-existing notification
     * GIVEN : A user is authenticated and the notification does not exist
     * WHEN : A request is made to delete a non-existing notification
     * THEN : An error message should be returned
     */
    public function test_delete_non_existing_notification_should_return_error(): void
    {
        // GIVEN : A user is authenticated and the notification does not exist

        // WHEN : A request is made to delete a non-existing notification
        $response = $this->actingAs($this->user)->delete('/api/details/999');

        // THEN : An error message should be returned
        $response->assertStatus(404);
        $response->assertJson(['message' => 'Notification not found.']);
    }
}
