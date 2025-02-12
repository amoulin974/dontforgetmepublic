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
            'numTel' => '0651334645',
            'email' => 'user@domain.com',
            'password' => 'Password123',
            'password_confirmation' => 'Password123',
        ], $overrides);
    }

    /**
     * CASE 1 - All valid
     * GIVEN : All valid
     * WHEN : A register attempt is made
     * THEN : The account should be created
     */
    #[Test]
    public function test_all_valid(): void
    {
        // GIVEN
        //$this->

        // WHEN
        $response = $this->post('/register', $this->registerData());

        // THEN
        $response->assertRedirect('/home');
        $this->assertDatabaseHas('users', [
            'email' => 'user@domain.com',
        ]);
    }

    /**
     * CASE 2 - Empty inputs
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
     * CASE 3 - Empty nom
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
     * CASE 4 - Empty prenom
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
     * CASE 5 - Empty email
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
     * CASE 6 - Empty password
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
     * CASE 7 - Empty password confirmation
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

    /**
     * CASE 8 - Invalid email
     * GIVEN : All valid but an invalid email is provided
     * WHEN : A register attempt is made
     * THEN : An error should be returned for the email field
     */
    #[Test]
    public function test_invalid_email_should_return_error(): void
    {
        // WHEN
        $response = $this->post('/register/user', $this->registerData(['email' => 'victodydy@']));

        // THEN
        $response->assertSessionHasErrors(['email']);
        $this->assertEquals(
            __('validation.email', ['attribute' => __('validation.attributes.email')]),
            session('errors')->first('email')
        );
    }

    /**
     * CASE 9 - Password and confirmation password different
     * GIVEN : All valid but password and confirmation password not matching
     * WHEN : A register attempt is made
     * THEN : An error should be returned for the confirmation password
     */
    #[Test]
    public function test_password_and_confirmation_should_match(): void
    {
        // WHEN
        $response = $this->post('/register/user', $this->registerData([
            'password' => 'Password123',
            'password_confirmation' => 'Password321'
        ]));

        // THEN
        $response->assertSessionHasErrors(['password']);
        $this->assertEquals(
            __('validation.confirmed', ['attribute' => __('validation.attributes.password')]),
            session('errors')->first('password')
        );
    }

    /**
     * CASE 10 - Password too short
     * GIVEN : All valid but password too short provided
     * WHEN : A register attempt is made
     * THEN : An error should be returned for the password field
     */
    #[Test]
    public function test_password_too_short_should_return_error(): void
    {
        // WHEN
        $response = $this->post('/register/user', $this->registerData(['password' => 'pass', 'password_confirmation' => 'pass']));

        // THEN
        $response->assertSessionHasErrors(['password']);
        $this->assertEquals(
            __('validation.min.string', ['attribute' => __('validation.attributes.password'), 'min' => 8]),
            session('errors')->first('password')
        );
    }

    /**
     * CASE 11 - Password too short
     * GIVEN : All valid but password too short provided
     * WHEN : A register attempt is made
     * THEN : An error should be returned for the password field
     */
    #[Test]
    public function test_email_with_uppercase_should_be_valid(): void
    {
        // WHEN
        $response = $this->post('/register', $this->registerData(['email' => 'VICTODYDY@GMAIL.COM']));

        // THEN
        $response->assertRedirect('/home');
        $this->assertDatabaseHas('users', [
            'email' => 'VICTODYDY@GMAIL.COM',
        ]);
    }

    /**
     * CASE 12 - Email containing script (XSS attempt)
     * GIVEN : An email containing potentially malicious JavaScript code
     * WHEN : A register attempt is made
     * THEN : An error should be returned for the email field
     */
    #[Test]
    public function test_email_with_script_injection_should_return_error(): void
    {
        // WHEN
        $response = $this->post('/register/user', $this->registerData(['email' => 'victodydy@gmail.com<script>']));

        // THEN
        $response->assertSessionHasErrors(['email']);
    }

    /**
     * CASE 13 - Protecting against brute force attacks
     * GIVEN : Multiple failed login attempts
     * WHEN : The user exceeds the maximum number of attempts
     * THEN : The user should be temporarily locked out
     */
    #[Test]
    public function test_brute_force_protection_should_block_attempts(): void
    {
        // Simuler plusieurs tentatives
        for ($i = 0; $i < 10; $i++) {
            $response = $this->post('/register/user', $this->registerData(['email' => 'user@domain.com', 'password' => 'wrongpass']));
        }

        // Vérifier si l'application bloque l'utilisateur après trop de tentatives
        $response->assertSessionHasErrors(['password']);
    }

}
