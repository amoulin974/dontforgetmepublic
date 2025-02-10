<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;
use App\Models\User;

class UpdateUserProfileTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Helper : Default data for profile edit
     */
    protected function profileEditData($overrides = [])
    {
        return array_merge([
            'nom' => 'Victora', 
            'prenom' => 'Dylann', 
            'email' => 'victodydy64wii@gmail.com',
            'numTel' => '0651334645',
            'typeNotif' => 'SMS',
            'delaiAvantNotif' => '1 jour'
        ], $overrides);
    }

    /**
     * Helper : Default data for user
     */
    protected function userDefaultData()
    {
        return User::factory()->create([
            'nom' => 'Test',
            'prenom' => 'User',
            'email' => 'user@domain.com',
            'password' => Hash::make('P@ssw0rd123!'),
        ]);
    }

    /**
     * CASE 1 - Correct inputs
     * GIVEN : All provided data is correct
     * WHEN : A profile edit attempt is made
     * THEN : The edit should work
     */
    #[Test]
    public function test_correct_data_in_all_fields_returns_a_successful_response(): void
    {
        // GIVEN
        $user = $this->userDefaultData();

        //WHEN
        $response = $this->actingAs($user)->put('/profile/edit', $this->profileEditData()); 

        //THEN
        $response->assertRedirect('/profile');
    }

    /**
     * CASE 2 - Empty inputs
     * GIVEN : No data is provided
     * WHEN : A profile edit attempt is made
     * THEN : Errors should be returned for the first name, the last name and the email.
     */
    #[Test]
    public function test_profile_edit_empty_inputs_should_return_error(): void
    {
        // GIVEN
        $user = $this->userDefaultData();

        // WHEN
        $response = $this->put('/profile/edit', $this->profileEditData([
            'nom' => '', 
            'prenom' => '', 
            'email' => '',
            'numTel' => '',
            'typeNotif' => '',
            'delaiAvantNotif' => '',
        ]));

        // THEN
        $response->assertSessionHasErrors(['nom', 'prenom', 'email']);
        $this->assertEquals(
            __('validation.required', ['attribute' => __('validation.attributes.first_name')]),
            session('errors')->first('nom')
        );
        $this->assertEquals(
            __('validation.required', ['attribute' => __('validation.attributes.last_name')]),
            session('errors')->first('prenom')
        );
        $this->assertEquals(
            __('validation.required', ['attribute' => __('validation.attributes.email')]),
            session('errors')->first('email')
        );
    }

    /**
     * CASE 3 - Empty first name
     * GIVEN : Valid data but an empty first name is provided
     * WHEN : A profile edit attempt is made
     * THEN : An error should be returned for the first name.
     */
    #[Test]
    public function test_profile_edit_empty_first_name_should_return_error(): void
    {
        // GIVEN
        $user = $this->userDefaultData();

        // WHEN
        $response = $this->put('/profile/edit', $this->profileEditData(['nom' => '']));

        // THEN
        $response->assertSessionHasErrors(['nom']);
        $this->assertEquals(
            __('validation.required', ['attribute' => __('validation.attributes.first_name')]),
            session('errors')->first('nom')
        );
    }

    /**
     * CASE 4 - Empty last name
     * GIVEN : Valid data but an empty last name is provided
     * WHEN : A profile edit attempt is made
     * THEN : An error should be returned for the last name.
     */
    #[Test]
    public function test_profile_edit_empty_last_name_should_return_error(): void
    {
        // GIVEN
        $user = $this->userDefaultData();

        // WHEN
        $response = $this->put('/profile/edit', $this->profileEditData(['prenom' => '']));

        // THEN
        $response->assertSessionHasErrors(['prenom']);
        $this->assertEquals(
            __('validation.required', ['attribute' => __('validation.attributes.last_name')]),
            session('errors')->first('prenom')
        );
    }

    /**
     * CASE 5 - Phone number with letters
     * GIVEN : Valid data but a phone number with letters is provided
     * WHEN : A profile edit attempt is made
     * THEN : An error should be returned for the phone number.
     */
    #[Test]
    public function test_profile_edit_phone_number_with_letters_should_return_error(): void
    {
        // GIVEN
        $user = $this->userDefaultData();

        // WHEN
        $response = $this->put('/profile/edit', $this->profileEditData(['numTel' => 'OGSIEE4G4S']));

        // THEN
        $response->assertSessionHasErrors(['numTel']);
        $this->assertEquals(
            __('validation.required', ['attribute' => __('validation.attributes.phone')]),
            session('errors')->first('numTel')
        );
    }

    /**
     * CASE 6 - Phone number with an invalid format
     * GIVEN : Valid data but a phone number with an invalid format is provided
     * WHEN : A profile edit attempt is made
     * THEN : An error should be returned for the phone number.
     */
    #[Test]
    public function test_profile_edit_invalid_phone_number_format_should_return_error(): void
    {
        // GIVEN
        $user = $this->userDefaultData();

        // WHEN
        $response = $this->put('/profile/edit', $this->profileEditData(['numTel' => '065133464']));

        // THEN
        $response->assertSessionHasErrors(['numTel']);
        $this->assertEquals(
            __('validation.required', ['attribute' => __('validation.attributes.phone')]),
            session('errors')->first('numTel')
        );
    }

    /**
     * CASE 7 - Empty email
     * GIVEN : Valid data but an empty email is provided
     * WHEN : A profile edit attempt is made
     * THEN : An error should be returned for the email.
     */
    #[Test]
    public function test_profile_edit_empty_email_should_return_error(): void
    {
        // GIVEN
        $user = $this->userDefaultData();

        // WHEN
        $response = $this->put('/profile/edit', $this->profileEditData(['email' => '',]));

        // THEN
        $response->assertSessionHasErrors(['email']);
        $this->assertEquals(
            __('validation.required', ['attribute' => __('validation.attributes.email')]),
            session('errors')->first('email')
        );
    }

    /**
     * CASE 8 - Uppercase email
     * GIVEN : Valid data but an uppercase email is provided
     * WHEN : A profile edit attempt is made
     * THEN : An error should be returned for the email.
     */
    #[Test]
    public function test_profile_edit_uppercase_email_should_return_error(): void
    {
        // GIVEN
        $user = $this->userDefaultData();

        // WHEN
        $response = $this->put('/profile/edit', $this->profileEditData(['email' => 'VICTODYDY64WII@GMAIL.COM']));

        // THEN
        $response->assertSessionHasErrors(['email']);
        $this->assertEquals(
            __('validation.lowercase', ['attribute' => __('validation.attributes.email')]),
            session('errors')->first('email')
        );
    }
}
