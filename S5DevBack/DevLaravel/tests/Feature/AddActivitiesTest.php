<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Activite;
use App\Models\User;
use App\Models\Entreprise;

class AddActivitiesTest extends TestCase
{
    use RefreshDatabase;
    
    protected function activitiesData($overrides = [])
    {
        return array_merge([
            'libelle' => 'Test',
            'duree' => '00:30',
        ], $overrides);
    }

    /**
     * CASE 1 - All valid
     * GIVEN : All valid
     * WHEN : An activity creation attempt is made
     * THEN : The activity should be created
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

        // WHEN
        $response = $this->post("/entreprise/{$entreprise->id}/services/store", $this->activitiesData());

        // THEN
        $response->assertRedirect("/entreprise/{$entreprise->id}/services");
        $this->assertDatabaseHas('activites', [
            'libelle' => 'Test',
        ]);
    }

    /**
     * CASE 2 - Empty inputs
     * GIVEN : No data is created
     * WHEN : An activity creation attempt is made
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

        // WHEN
        $response = $this->post("/entreprise/{$entreprise->id}/services/store", $this->activitiesData(['libelle' => '', 'duree' => '']));

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
     * GIVEN : No libelle
     * WHEN : An activity creation attempt is made
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

        // WHEN
        $response = $this->post("/entreprise/{$entreprise->id}/services/store", $this->activitiesData(['libelle' => '']));

        // THEN
        $response->assertSessionHasErrors(['libelle']);
        $this->assertEquals(
            __('validation.required', ['attribute' => 'libelle']),
            session('errors')->first('libelle')
        );
    }

    /**
     * CASE 4 - Empty duree
     * GIVEN : No duree
     * WHEN : An activity creation attempt is made
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

        // WHEN
        $response = $this->post("/entreprise/{$entreprise->id}/services/store", $this->activitiesData(['duree' => '']));

        // THEN
        $response->assertSessionHasErrors(['duree']);
        $this->assertEquals(
            __('validation.required', ['attribute' => 'duree']),
            session('errors')->first('duree')
        );
    }
}
