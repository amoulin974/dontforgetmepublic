<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;
use App\Models\User;

class LoginTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Helper : Default data for login
     */
    protected function loginData($overrides = [])
    {
        return array_merge([
            'email' => 'user@domain.com',
            'password' => 'Password123',
        ], $overrides);
    }


    /**
     * CASE 1 - Empty inputs
     * GIVEN : No data is provided
     * WHEN : AA login attempt is made
     * THEN : Errors should be returned for both fields
     */
    #[Test]
    public function test_empty_inputs_should_return_error(): void
    {
        // WHEN
        $response = $this->post('/login', $this->loginData(['email' => '', 'password' => '']));

        // THEN
        $response->assertSessionHasErrors(['email', 'password']);
        $this->assertEquals(
            __('validation.required', ['attribute' => __('validation.attributes.email')]),
            session('errors')->first('email')
        );
        $this->assertEquals(
            __('validation.required', ['attribute' => __('validation.attributes.password')]),
            session('errors')->first('password')
        );
    }

    /**
     * CASE 2 - Empty password
     * GIVEN : A valid email but an empty passwrd is provided
     * WHEN : A login attempt is made
     * THEN : An error should be returned for the password field
     */
    #[Test]
    public function test_empty_password_should_return_error(): void
    {
        // WHEN
        $response = $this->post('/login', $this->loginData(['password' => '']));

        // THEN
        $response->assertSessionHasErrors(['password']);
        $this->assertEquals(
            __('validation.required', ['attribute' => __('validation.attributes.password')]),
            session('errors')->first('password')
        );
    }

    /**
     * CASE 3 - Empty email
     * GIVEN : A valid password but an empty email is provided
     * WHEN : A login attempt is made
     * THEN : An error should be returned for the email field
     */
    #[Test]
    public function test_empty_email_should_return_error(): void
    {
        // WHEN
        $response = $this->post('/login', $this->loginData(['email' => '']));

        // THEN
        $response->assertSessionHasErrors(['email']);
        $this->assertEquals(
            __('validation.required', ['attribute' => __('validation.attributes.email')]),
            session('errors')->first('email')
        );
    }

    /**
     * CASE 4 - Invalid email format
     * GIVEN : An invalid email format is provided
     * WHEN : A login attempt is made
     * THEN : An error should be returned for the email field
     */
    #[Test]
    public function test_invalid_email_format_should_return_error(): void
    {
        // WHEN
        $response = $this->post('/login', $this->loginData(['email' => 'user@domain']));

        // THEN
        $response->assertSessionHasErrors(['email']);
        $this->assertEquals(
            __('auth.failed'),
            session('errors')->first('email')
        );
    }

    /**
     * CASE 5 - Wrong password
     * GIVEN : Correct email but wrong password
     * WHEN : A login attempt is made
     * THEN : An error should be returned for the password field
     */
    #[Test]
    public function test_wrong_password_should_return_error(): void
    {
        // GIVEN
        $user = User::factory()->create([
            'nom' => 'Test',
            'prenom' => 'User',
            'email' => 'user@domain.com',
            'password' => Hash::make('Password123'),
        ]);

        // WHEN
        $response = $this->post('/login', $this->loginData(['password' => 'wrongpassword']));

        // THEN
        $response->assertSessionHasErrors(['email']);
        $this->assertEquals(
            __('auth.failed'),
            session('errors')->first('email')
        );
    }

    /**
     * CASE 6 - Correct credentials
     * GIVEN : Valid user credentials
     * WHEN : A login attempt is made
     * THEN : The user should be logged in and redirected to /home
     */
    #[Test]
    public function test_correct_credentials_should_log_successfully(): void
    {
        // GIVEN
        $user = User::factory()->create([
            'nom' => 'Test',
            'prenom' => 'User',
            'email' => 'user@domain.com',
            'password' => Hash::make('Password123'),
        ]);

        // WHEN
        $response = $this->post('/login', $this->loginData());

        // THEN
        $response->assertRedirect('/home');
        $this->assertAuthenticatedAs($user);
    }

    /**
     * CASE 7 - Correct credentials but email in full uppercase
     * GIVEN : Correct user credentials but the email is in full uppercase
     * WHEN : A login attempt is made
     * THEN : An error should be returned for the email field
     */
    #[Test]
    public function test_with_uppercase_email_should_return_error(): void
    {
        // GIVEN
        $user = User::factory()->create([
            'nom' => 'Test',
            'prenom' => 'User',
            'email' => 'user@domain.com',
            'password' => Hash::make('Password123'),
        ]);

        // WHEN
        $response = $this->post('/login', $this->loginData(['email' => 'USER@DOMAIN.COM']));

        // THEN
        $response->assertSessionHasErrors(['email']);
        $this->assertEquals(
            __('auth.failed'),
            session('errors')->first('email')
        );
    }

    /**
     * CASE 8 - Correct email but incorrect password
     * GIVEN : Correct email but incorrect password
     * WHEN : A login attempt is made
     * THEN : An error should be returned indicating invalid credentials
     */
    #[Test]
    public function test_invalid_password_should_return_error(): void
    {
        // GIVEN
        $user = User::factory()->create([
            'nom' => 'Test',
            'prenom' => 'User',
            'email' => 'user@domain.com',
            'password' => Hash::make('Password123'),
        ]);

        // WHEN
        $response = $this->post('/login', $this->loginData(['password' => 'password123']));

        // THEN
        $response->assertSessionHasErrors(['email']);
        $this->assertEquals(
            __('auth.failed'),
            session('errors')->first('email')
        );
    }

    /**
     * CASE 9 - Not existing user
     * GIVEN : A non-existing user
     * WHEN : A login attempt is made
     * THEN : An error should be returned indicating invalid credentials
     */
    #[Test]
    public function test_user_not_existing_should_return_error(): void
    {
        // GIVEN
        $user = User::factory()->create([
            'nom' => 'Test',
            'prenom' => 'User',
            'email' => 'user@domain.com',
            'password' => Hash::make('Password123'),
        ]); 

        // WHEN
        $response = $this->post('/login', $this->loginData(['email' => 'user@nonexistant.com']));

        // THEN
        $response->assertSessionHasErrors(['email']);
        $this->assertEquals(
            __('auth.failed'),
            session('errors')->first('email')
        );
    }

    /**
     * CASE 10 - Email containing script (XSS attempt)
     * GIVEN : An email containing potentially malicious JavaScript code
     * WHEN : A login attempt is made
     * THEN : An error should be returned for the email field
     */
    #[Test]
    public function test_xss_should_return_error(): void
    {
        // WHEN
        $response = $this->post('/login', $this->loginData(['email' => 'user@domain.com<script>']));

        // THEN
        $response->assertSessionHasErrors(['email']);
        $this->assertEquals(
            __('auth.failed'),
            session('errors')->first('email')
        );
    }

    /**
     * CASE 11 - Correct credentials with password containing special caracters
     * GIVEN : A valid email and a valid password containing special caracters
     * WHEN : A login attempt is made
     * THEN : The user should be logged in successfully
     */
    #[Test]
    public function test_special_caracters_should_log_successfully(): void
    {
        // GIVEN
        $user = User::factory()->create([
            'nom' => 'Test',
            'prenom' => 'User',
            'email' => 'user@domain.com',
            'password' => Hash::make('P@ssw0rd123!'),
        ]); 

        // WHEN
        $response = $this->post('/login', $this->loginData(['password' => 'P@ssw0rd123!']));

        // THEN
        $response->assertRedirect('/home');
        $this->assertAuthenticatedAs($user);
    }

    /**
     * CASE 12 - Protecting against brute force attacks
     * GIVEN : Multiple failed login attempts
     * WHEN : The user exceeds the maximum number of attempts
     * THEN : The user should be temporarily locked out
     */
    #[Test]
    public function test_brute_force_protection_should_lock_user(): void
    {
        // GIVEN
        $MAX_TENTATIVES = 5;
        $user = User::factory()->create([
            'nom' => 'Test',
            'prenom' => 'User',
            'email' => 'user@domain.com',
            'password' => Hash::make('Password123'),
        ]);

        // WHEN
        for ($i = 0; $i < $MAX_TENTATIVES + 1; $i++) {
            $response = $this->post('/login', $this->loginData(['password' => 'wrongpassword']));
        }

        // THEN
        $response->assertSessionHasErrors(['email']);
        /* $this->assertEquals(
            __('auth.throttle', ['seconds' => 59]),
            session('errors')->first('email')
        ); */
        $this->assertTrue(
            str_contains(session('errors')->first('email'), __('auth.throttle', ['seconds' => 59])) ||
            str_contains(session('errors')->first('email'), __('auth.throttle', ['seconds' => 60]))
        );
    }
}
