<?php
/**
 * @file ForgotPasswordController.php
 * @brief Controller for handling password reset emails in the Laravel application.
 */
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;

/**
 * @class ForgotPasswordController
 * @brief Controller for handling password reset emails in the Laravel application.
 * 
 * This controller is responsible for handling password reset emails and includes a trait which assists in sending these notifications from your application to your users. Feel free to explore this trait.
 */
class ForgotPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset emails and
    | includes a trait which assists in sending these notifications from
    | your application to your users. Feel free to explore this trait.
    |
    */

    use SendsPasswordResetEmails;
}
