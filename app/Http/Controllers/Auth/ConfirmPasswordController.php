<?php
/**
 * @file ConfirmPasswordController.php
 * @brief Controller for handling password confirmations in the Laravel application.
 */
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ConfirmsPasswords;

/**
 * @class ConfirmPasswordController
 * @brief Controller for handling password confirmations in the Laravel application.
 * 
 * This controller is responsible for handling password confirmations and uses a simple trait to include the behavior.
 */
class ConfirmPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Confirm Password Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password confirmations and
    | uses a simple trait to include the behavior. You're free to explore
    | this trait and override any functions that require customization.
    |
    */

    use ConfirmsPasswords;

    /**
     * Redirect path when the intended URL fails.
     *
     * @var string $redirectTo
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * This applies the authentication middleware to ensure only logged-in users
     * can access password confirmation operations.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }
}
