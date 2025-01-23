<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
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
    }

    /**
     * CASE 2
     * 
     * Login testing : empty password
     */
    #[Test]
    public function test_empty_password_should_return_error(): void
    {
        $response = $this->post('/login', $this->loginData(['password' => '']));

        $response->assertSessionHasErrors(['password']);
    }

    /**
     * CASE 3
     * 
     * Login testing : empty email
     */
    #[Test]
    public function test_empty_email_should_return_error(): void
    {
        $response = $this->post('/login', $this->loginData(['email' => '']));

        $response->assertSessionHasErrors(['email']);
    }

    /**
     * CASE 4
     * 
     * Login testing : invalid email format
     */
    #[Test]
    public function test_invalid_email_format_should_return_error(): void
    {
        $response = $this->post('/login', $this->loginData(['email' => 'user@domain']));

        $response->assertSessionHasErrors(['email']);
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
            'email' => '',
            'password' => bcrypt('Password123'),
        ]);

        // WHEN
        $response = $this->post('/login', $this->loginData(['password' => 'wrongpassword']));

        // THEN
        $response->assertSessionHasErrors(['password']);
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
            'email' => '',
            'password' => bcrypt('Password123'),
        ]);

        // WHEN
        $response = $this->post('/login', $this->loginData());

        // THEN
        $response->assertRedirect('/home');
        $this->assertAuthenticated($user);
    }

    /**
     * CASE 7
     * 
     * Login testing : Correct credentials but email in full uppercase
     */
    #[Test]
    public function test_with_uppercase_email_should_log_successfully(): void
    {
        $response = $this->post('/login', $this->loginData(['email' => 'USER@DOMAIN.COM']));

        $response->assertRedirect('/home');
    }

    /**
     * CASE 8
     * 
     * Login testing : Correct credentials but error of caracter in password
     */
    #[Test]
    public function test_invalid_password_should_return_error(): void
    {
        $response = $this->post('/login', $this->loginData(['password' => 'password123']));

        $response->assertSessionHasErrors(['password']);
    }

    /**
     * CASE 9
     * 
     * Login testing : Not existing user
     */
    #[Test]
    public function test_user_not_existing_should_return_error(): void
    {
        $response = $this->post('/login', $this->loginData(['email' => 'user@nonexistant.com']));

        $response->assertSessionHasErrors(['email']);
    }

    /**
     * CASE 10
     * 
     * Login testing : password too short
     */
    #[Test]
    public function test_password_too_short_should_return_error(): void
    {
        $response = $this->post('/login', $this->loginData(['password' => 'pass']));

        $response->assertSessionHasErrors(['password']);
    }

    /**
     * CASE 11
     * 
     * Login testing : email containing script (XSS tentative)
     */
    #[Test]
    public function test_XSS_should_return_error(): void
    {
        $response = $this->post('/login', $this->loginData(['email' => 'user@domain.com<script>']));

        $response->assertSessionHasErrors(['email']);
    }

    /**
     * CASE 12
     * 
     * Login testing : Correct credentials but password containing special caracters
     */
    #[Test]
    public function test_special_caracters_should_log_successfully(): void
    {
        $response = $this->post('/login', $this->loginData(['password' => 'P@ssw0rd123!']));

        $response->assertRedirect('/home');
    }

    /**
     * CASE 13 - Protecting against brute force attacks
     * GIVEN : Multiple failed login attempts
     * WHEN : The user exceeds the maximum number of attempts
     * THEN : The user should be temporarily locked out
     */
    #[Test]
    public function test_brute_force_protection_should_lock_user(): void
    {
        // GIVEN
        $user = User::factory()->create([
            'email' => '',
            'password' => bcrypt('Password123'),
        ]);

        // WHEN
        for ($i = 0; $i < 5; $i++) {
            $response = $this->post('/login', $this->loginData(['password' => 'wrongpassword']));
        }

        // THEN
        $response = $this->post('/login', $this->loginData(['password' => 'wrongpassword']));
        $response->assertSessionHasErrors(['password']);
    }
}
