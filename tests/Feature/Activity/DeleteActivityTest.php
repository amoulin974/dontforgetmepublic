<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Activite;
use App\Models\User;
use App\Models\Entreprise;

class DeleteActivityTest extends TestCase
{
    use RefreshDatabase;

    /**
     * CASE 1 - All valid
     * GIVEN : All valid
     * WHEN : An activity removing attempt is made
     * THEN : The activity should be deleted
     */
    #[Test]
    public function test_all_valid(): void
    { 
        // GIVEN
        $user = User::factory()->create();
        $this->actingAs($user); 

        $entreprise = Entreprise::factory()->create([
            'idCreateur' => $user->id, 
        ]); 

        $activite = Activite::factory()->create([
            'idEntreprise' => $entreprise->id, 
        ]); 

        // WHEN
        $response = $this->delete("/entreprise/{$entreprise->id}/services/{$activite->id}");

        // THEN
        $response->assertRedirect("/entreprise/{$entreprise->id}/services");
    }
}
