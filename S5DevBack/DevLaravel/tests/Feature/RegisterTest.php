<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;
use App\Models\User;

class RegisterTest extends TestCase
{
    use RefreshDatabase;
    
    /**
     * Helper : Default data for login
     */
    protected function registerData($overrides = [])
    {
        return array_merge([
            'nom' => 'Victoras',
            'prenom' => 'Dylan',
            'email' => 'user@domain.com',
            'password' => 'Password123',
            'password_confirmation' => 'Password123',
        ], $overrides);
    }

    /**
     * CASE 1 - Empty inputs
     * GIVEN : No data is provided
     * WHEN : AA register attempt is made
     * THEN : Errors should be returned for all fields
     */
    #[Test]
    public function test_empty_inputs_should_return_error(): void
    {
        // WHEN
        $response = $this->post('/register/user', $this->registerData(['nom' => '', 'prenom' => '',  'email' => '', 'password' => '', 'password_confirmation' => '']));

        // THEN
        $response->assertSessionHasErrors(['nom', 'prenom', 'email', 'password', 'password_confirmation']);
        $this->assertEquals(
            __('validation.required', ['attribute' => 'nom']),
            session('errors')->first('nom')
        );
        $this->assertEquals(
            __('validation.required', ['attribute' => 'prenom']),
            session('errors')->first('prenom')
        );
        $this->assertEquals(
            __('validation.required', ['attribute' => __('validation.attributes.email')]),
            session('errors')->first('email')
        );
        $this->assertEquals(
            __('validation.required', ['attribute' => __('validation.attributes.password')]),
            session('errors')->first('password')
        );
        $this->assertEquals(
            __('validation.required', ['attribute' => __('validation.attributes.password_confirmation')]),
            session('errors')->first('password_confirmation')
        );
    }

    /**
     * CASE 2 - Empty nom
     * GIVEN : All valid but an empty nom is provided
     * WHEN : A register attempt is made
     * THEN : An error should be returned for the nom field
     */
    #[Test]
    public function test_empty_surname_should_return_error(): void
    {
        // WHEN
        $response = $this->post('/register/user', $this->registerData(['nom' => '']));

        // THEN
        $response->assertSessionHasErrors(['nom']);
        $this->assertEquals(
            __('validation.required', ['attribute' => 'nom']),
            session('errors')->first('nom')
        );
    }

    /**
     * CASE 3 - Empty prenom
     * GIVEN : All valid but an empty prenom is provided
     * WHEN : A register attempt is made
     * THEN : An error should be returned for the prenom field
     */
    #[Test]
    public function test_empty_name_should_return_error(): void
    {
        // WHEN
        $response = $this->post('/register/user', $this->registerData(['prenom' => '']));

        // THEN
        $response->assertSessionHasErrors(['prenom']);
        $this->assertEquals(
            __('validation.required', ['attribute' => 'prenom']),
            session('errors')->first('prenom')
        );
    }

    /**
     * CASE 4 - Empty email
     * GIVEN : All valid but an empty email is provided
     * WHEN : A register attempt is made
     * THEN : An error should be returned for the email field
     */
    #[Test]
    public function test_empty_email_should_return_error(): void
    {
        // WHEN
        $response = $this->post('/register/user', $this->registerData(['email' => '']));

        // THEN
        $response->assertSessionHasErrors(['email']);
        $this->assertEquals(
            __('validation.required', ['attribute' => __('validation.attributes.email')]),
            session('errors')->first('email')
        );
    }

    /**
     * CASE 4 - Empty password
     * GIVEN : All valid but an empty password is provided
     * WHEN : A register attempt is made
     * THEN : An error should be returned for the password field
     */
    #[Test]
    public function test_empty_password_should_return_error(): void
    {
        // WHEN
        $response = $this->post('/register/user', $this->registerData(['password' => '']));

        // THEN
        $response->assertSessionHasErrors(['password']);
        $this->assertEquals(
            __('validation.required', ['attribute' => __('validation.attributes.password')]),
            session('errors')->first('password')
        );
    }

    /**
     * CASE 4 - Empty password confirmation
     * GIVEN : All valid but an empty password confirmation is provided
     * WHEN : A register attempt is made
     * THEN : An error should be returned for the password confirmation field
     */
    #[Test]
    public function test_empty_password_confirmation_should_return_error(): void
    {
        // WHEN
        $response = $this->post('/register/user', $this->registerData(['password_confirmation' => '']));

        // THEN
        $response->assertSessionHasErrors(['password_confirmation']);
        $this->assertEquals(
            __('validation.required', ['attribute' => __('validation.attributes.password_confirmation')]),
            session('errors')->first('password_confirmation')
        );
    }

}
