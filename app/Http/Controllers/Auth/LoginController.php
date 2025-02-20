<?php
/**
 * @file LoginController.php
 * @brief Controller for handling user authentication and login in the Laravel application.
 */
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

/**
 * @class LoginController
 * @brief Controller for handling user authentication and login in the Laravel application.
 *
 * This controller manages authentication using Laravel's built-in authentication system.
 * It provides functionalities for user login, token-based authentication, and redirections.
 */
class LoginController extends Controller
{
    use AuthenticatesUsers;

    /**
     * @var string $redirectTo The path to redirect users after login.
     */
    protected $redirectTo = '/home';

    /**
     * @brief Initializes the controller instance.
     *
     * Applies middleware to restrict access:
     * - Guest users can access login methods.
     * - Authenticated users can only access logout.
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        $this->middleware('auth')->only('logout');
    }

    /**
     * @brief Authenticates a user and returns a token if credentials are valid.
     *
     * @param Request $request The HTTP request containing login credentials.
     *
     * @return \Illuminate\Http\JsonResponse A JSON response containing the token or an error message.
     */
    public function tokenLogin(Request $request)
    {
        // Validate request data
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:8',
        ]);

        // Retrieve the user by email
        $user = User::where('email', $request->email)->first();

        // Check if user exists and password is correct
        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }
        // Check if the user is a superadmin
        if ($user->superadmin != 1) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        // Generate a token for the authenticated superadmin user
        $token = $user->createToken('YourAppName')->plainTextToken;

        return response()->json(['token' => $token]);
    }
}
