<?php
/**
 * @file ResetPasswordController.php
 * @brief Controller for handling password resets in the Laravel application.
 */
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ResetsPasswords;

/**
 * @class ResetPasswordController
 * @brief Controller for handling password resets in the Laravel application.
 *
 * This controller manages the process of resetting user passwords.
 * It uses Laravel's built-in ResetsPasswords trait, which provides
 * the necessary functionality for password reset handling.
 */
class ResetPasswordController extends Controller
{
    use ResetsPasswords;
    /**
     * @var string $redirectTo The path to redirect users after resetting their password.
     */
    protected $redirectTo = '/home';
}
