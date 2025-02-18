<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\VerifiesEmails;

/**
 * @class VerificationController
 *
 * @brief Handles email verification for registered users.
 *
 * This controller manages the process of verifying user email addresses
 * after registration. It allows users to verify their emails and resend
 * verification emails if needed.
 */
class VerificationController extends Controller
{
    use VerifiesEmails;

    /**
     * @var string $redirectTo The path to redirect users after verification.
     */
    protected $redirectTo = '/home';

    /**
     * @brief Initializes the controller instance.
     *
     * Applies middleware to restrict access to authenticated users,
     * enforce signed verification links, and throttle email verification attempts.
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('signed')->only('verify');
        $this->middleware('throttle:6,1')->only('verify', 'resend');
    }
}
