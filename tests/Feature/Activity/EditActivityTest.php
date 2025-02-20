<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Activite;
use App\Models\User;
use App\Models\Entreprise;

class EditActivityTest extends TestCase
{
    use RefreshDatabase;

    protected function activityData($overrides = [])
    {
        return array_merge([
            'libelle' => 'Test',
            'duree' => '00:30',
            'nbrPlaces' => 10,
        ], $overrides);
    }

    /**
     * CASE 1 - All valid
     * GIVEN : All valid
     * WHEN : An activity update attempt is made
     * THEN : The activity should be updated
     */
    #[Test]
    public function test_all_valid(): void
    { 
        // GIVEN
        $user = User::factory()->create();
        $this->actingAs($user); 

        $entreprise = Entreprise::factory()->create([
            'capaciteMax' => 10,
            'idCreateur' => $user->id, 
        ]); 

        $activite = Activite::factory()->create([
            'nbrPlaces' => 10,
            'idEntreprise' => $entreprise->id, 
        ]); 

        // WHEN
        $response = $this->put("/entreprise/{$entreprise->id}/services/{$activite->id}", $this->activityData());

        // THEN
        $response->assertRedirect("/entreprise/{$entreprise->id}/services");
        $this->assertDatabaseHas('activites', [
            'libelle' => 'Test',
        ]);
    }

    /**
     * CASE 2 - Empty inputs
     * GIVEN : No data is given
     * WHEN : An activity update attempt is made
     * THEN : Error should be returned to all fields
     */
    #[Test]
    public function test_empty_inputs_should_return_error(): void
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
        $response = $this->put("/entreprise/{$entreprise->id}/services/{$activite->id}", $this->activityData(['libelle' => '', 'duree' => '']));

        // THEN
        $response->assertSessionHasErrors(['libelle', 'duree']);
        $this->assertEquals(
            __('validation.required', ['attribute' => 'libelle']),
            session('errors')->first('libelle')
        );
        $this->assertEquals(
            __('validation.required', ['attribute' => 'duree']),
            session('errors')->first('duree')
        );
    }

    /**
     * CASE 3 - Empty libelle
     * GIVEN : No libelle given
     * WHEN : An activity update attempt is made
     * THEN : Error should be returned to libelle field
     */
    #[Test]
    public function test_empty_libelle_should_return_error(): void
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
        $response = $this->put("/entreprise/{$entreprise->id}/services/{$activite->id}", $this->activityData(['libelle' => '']));

        // THEN
        $response->assertSessionHasErrors(['libelle']);
        $this->assertEquals(
            __('validation.required', ['attribute' => 'libelle']),
            session('errors')->first('libelle')
        );
    }

    /**
     * CASE 4 - Empty duree
     * GIVEN : No duree given
     * WHEN : An activity update attempt is made
     * THEN : Error should be returned to duree field
     */
    #[Test]
    public function test_empty_duree_should_return_error(): void
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
        $response = $this->put("/entreprise/{$entreprise->id}/services/{$activite->id}", $this->activityData(['duree' => '']));

        // THEN
        $response->assertSessionHasErrors(['duree']);
        $this->assertEquals(
            __('validation.required', ['attribute' => 'duree']),
            session('errors')->first('duree')
        );
    }
}
