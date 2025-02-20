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
            'last_name' => 'Victora', 
            'first_name' => 'Dylann', 
            'email' => 'victodydy64wii@gmail.com',
            'phone' => '0651334645',
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
     * GIVEN : A logged in user and valid profile edit data
     * WHEN : A profile update attempt is submitted
     * THEN : The profile should be updated successfully and the user should be redirected to the profile page.
     */
    #[Test]
    public function test_correct_data_in_all_fields_returns_a_successful_response(): void
    {
        // GIVEN
        $user = $this->userDefaultData();

        //WHEN
        $response = $this->actingAs($user)->put('/profile/update', $this->profileEditData(), [
            'Accept' => 'application/json',
        ]);

        //THEN
        $response->assertRedirect(route('profile.index'));
    }

    /**
     * CASE 2 - Empty inputs
     * GIVEN : A logged in user and empty profile edit form
     * WHEN : The user submits the form with no data
     * THEN : Errors should be returned for the first name, last name and email.
     */
    #[Test]
    public function test_profile_edit_empty_inputs_should_return_error(): void
    {
        // GIVEN
        $user = $this->userDefaultData();

        // WHEN
        $response = $this->actingAs($user)->put('/profile/update', $this->profileEditData([
            'last_name' => '', 
            'first_name' => '', 
            'email' => '',
            'phone' => '',
            'typeNotif' => '',
            'delaiAvantNotif' => '',
        ]));

        // THEN
        $response->assertSessionHasErrors(['last_name', 'first_name', 'email']);
        $this->assertEquals(
            __('validation.required', ['attribute' => __('validation.attributes.last_name')]),
            session('errors')->first('last_name')
        );
        $this->assertEquals(
            __('validation.required', ['attribute' => __('validation.attributes.first_name')]),
            session('errors')->first('first_name')
        );
        $this->assertEquals(
            __('validation.required', ['attribute' => __('validation.attributes.email')]),
            session('errors')->first('email')
        );
    }

    /**
     * CASE 3 - Empty last name
     * GIVEN : A logged in user and valid data but an empty last name is provided
     * WHEN : The user submits the form with an empty last name
     * THEN : An error should be returned for the last name.
     */
    #[Test]
    public function test_profile_edit_empty_last_name_should_return_error(): void
    {
        // GIVEN
        $user = $this->userDefaultData();

        // WHEN
        $response = $this->actingAs($user)->put('/profile/update', $this->profileEditData(['last_name' => '']));

        // THEN
        $response->assertSessionHasErrors(['last_name']);
        $this->assertEquals(
            __('validation.required', ['attribute' => __('validation.attributes.last_name')]),
            session('errors')->first('last_name')
        );
    }

    /**
     * CASE 4 - Empty first name
     * GIVEN : A logged in user and valid data but an empty first name is provided
     * WHEN : The user submits the form with an empty first name
     * THEN : An error should be returned for the first name.
     */
    #[Test]
    public function test_profile_edit_empty_first_name_should_return_error(): void
    {
        // GIVEN
        $user = $this->userDefaultData();

        // WHEN
        $response = $this->actingAs($user)->put('/profile/update', $this->profileEditData(['first_name' => '']));

        // THEN
        $response->assertSessionHasErrors(['first_name']);
        $this->assertEquals(
            __('validation.required', ['attribute' => __('validation.attributes.first_name')]),
            session('errors')->first('first_name')
        );
    }

    /**
     * CASE 5 - Phone number with letters
     * GIVEN : A logged in user and valid profile data, except for a phone number with letters
     * WHEN : The user submits the form with an invalid phone number
     * THEN : A validation error should be returned for the 'numTel' due to the regex rule
     */
    #[Test]
    public function test_profile_edit_phone_number_with_letters_should_return_error(): void
    {
        // GIVEN
        $user = $this->userDefaultData();

        // WHEN
        $response = $this->actingAs($user)->put('/profile/update', $this->profileEditData(['phone' => 'OGSIEE4G4S']));

        // THEN
        $response->assertSessionHasErrors(['phone']);
        $this->assertEquals(
            __('validation.regex', ['attribute' => __('validation.attributes.phone')]),
            session('errors')->first('phone')
        );
    }

    /**
     * CASE 6 - Phone number with an invalid format
     * GIVEN : A logged in user and valid profile data, except for a phone number with an invalid format
     * WHEN : The user submits the form with an improperly formatted phone number
     * THEN : A validation error should be returned for the 'numTel' due to the regex rule
     */
    #[Test]
    public function test_profile_edit_invalid_phone_number_format_should_return_error(): void
    {
        // GIVEN
        $user = $this->userDefaultData();

        // WHEN
        $response = $this->actingAs($user)->put('/profile/update', $this->profileEditData(['phone' => '065133464']));

        // THEN
        $response->assertSessionHasErrors(['phone']);
        $this->assertEquals(
            __('validation.regex', ['attribute' => __('validation.attributes.phone')]),
            session('errors')->first('phone')
        ); 
    }

    /**
     * CASE 7 - Empty email
     * GIVEN : A logged in user and valid data but an empty email is provided
     * WHEN : The user submits the form with an empty email field
     * THEN : A validation error should be returned for the email.
     */
    #[Test]
    public function test_profile_edit_empty_email_should_return_error(): void
    {
        // GIVEN
        $user = $this->userDefaultData();

        // WHEN
        $response = $this->actingAs($user)->put('/profile/update', $this->profileEditData(['email' => '',]));

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
        $response = $this->actingAs($user)->put('/profile/update', $this->profileEditData(['email' => 'VICTODYDY64WII@GMAIL.COM']));

        // THEN
        $response->assertSessionHasErrors(['email']);
        $this->assertEquals(
            __('validation.lowercase', ['attribute' => __('validation.attributes.email')]),
            session('errors')->first('email')
        );
    }
}
