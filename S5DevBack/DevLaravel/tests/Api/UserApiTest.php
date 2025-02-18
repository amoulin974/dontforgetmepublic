<?php

namespace Tests\Api;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
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
     */
    public function test_get_details_should_return_notification_data(): void
    {
        $response = $this->actingAs($this->user)->get('/api/details');

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
     */
    public function test_update_notification_state_should_return_success(): void
    {
        $reservation = Reservation::factory()->create();
        $notification = Notification::factory()->create([
            'reservation_id' => $reservation->id,
            'etat' => false,
        ]);

        $response = $this->actingAs($this->user)->patch('/api/details/' . $notification->id, ['etat' => true]);

        $response->assertStatus(200);
        $response->assertJson(['message' => 'Notification updated successfully.']);
        $this->assertDatabaseHas('notifications', ['id' => $notification->id, 'etat' => true]);
    }

    /**
     * CASE 3 - Invalid update notification state (invalid data)
     */
    /**
     * CASE 3 - Invalid update notification state (invalid data)
     */
    public function test_update_notification_state_with_invalid_data_should_return_error(): void
    {
        $reservation = Reservation::factory()->create();
        $notification = Notification::factory()->create([
            'reservation_id' => $reservation->id,
        ]);

        $response = $this->actingAs($this->user)
            ->patch('/api/details/' . $notification->id, ['etat' => 'invalid'], ['Accept' => 'application/json']);


        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['etat']);
    }


    /**
     * CASE 4 - Delete notification and associated reservation details
     */
    public function test_delete_notification_and_associated_data_should_return_success(): void
    {
        $reservation = Reservation::factory()->create();
        $notification = Notification::factory()->create([
            'reservation_id' => $reservation->id,
        ]);

        $response = $this->actingAs($this->user)->delete('/api/details/' . $notification->id);

        $response->assertStatus(200);
        $response->assertJson(['message' => 'Notification and associated reservation details deleted successfully.']);
        $this->assertDatabaseMissing('notifications', ['id' => $notification->id]);
    }

    /**
     * CASE 5 - Delete non-existing notification
     */
    public function test_delete_non_existing_notification_should_return_error(): void
    {
        $response = $this->actingAs($this->user)->delete('/api/details/999');

        $response->assertStatus(404);
        $response->assertJson(['message' => 'Notification not found.']);
    }
}
