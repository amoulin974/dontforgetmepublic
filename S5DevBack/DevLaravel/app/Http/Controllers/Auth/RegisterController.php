<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Entreprise;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

/**
 * @class RegisterController
 *
 * @brief Handles user registration and validation.
 *
 * This controller manages the registration of new users, their validation,
 * and the creation of their accounts. It also provides account-type selection
 * and user registration page views.
 */
class RegisterController extends Controller
{
    use RegistersUsers;

    /**
     * @var string $redirectTo The path to redirect users after registration.
     */
    protected $redirectTo = '/home';

    /**
     * @brief Initializes the controller instance.
     *
     * Applies middleware to restrict access to guest users only.
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * @brief Validates an incoming registration request.
     *
     * @param array $data The user-provided data for validation.
     * @return \Illuminate\Contracts\Validation\Validator The validation instance.
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'nom' => ['required', 'string', 'max:255'],
            'prenom' => ['required', 'string', 'max:255'],
            'numTel' => ['nullable', 'string', 'max:15', 'regex:/^(\d{2} \d{2} \d{2} \d{2} \d{2}|\d{10})$/'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
    }

    /**
     * @brief Creates a new user instance after successful registration.
     *
     * @param array $data The validated user data.
     * @return \App\Models\User The created user instance.
     */
    protected function create(array $data)
    {
        return User::create([
            'nom' => $data['nom'],
            'prenom' => $data['prenom'],
            'numTel' => $data['numTel'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);
    }

    /**
     * @brief Displays the account type selection page.
     *
     * @return \Illuminate\View\View The view for selecting account type.
     */
    public function showChoicePage()
    {
        return view('auth.choose-account-type');
    }

    /**
     * @brief Displays the user registration page.
     *
     * @return \Illuminate\View\View The view for user registration.
     */
    public function showUserRegisterPage()
    {
        return view('auth.user-register');
    }

    /**
     * @brief Stores user data in the database after validation.
     *
     * @param \Illuminate\Http\Request $request The HTTP request containing user details.
     * @return \Illuminate\Http\RedirectResponse A redirect response after successful registration.
     */
    public function storeUser(Request $request)
    {
        $validated = $request->validate([
            'nom' => ['required', 'string', 'max:255'],
            'prenom' => ['required', 'string', 'max:255'],
            'numTel' => ['nullable', 'string', 'max:15', 'regex:/^(\d{2} \d{2} \d{2} \d{2} \d{2}|\d{10})$/'],
            'email' => ['required', 'email', 'unique:users,email', 'max:255'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        // Store user data
        $user = $this->create($validated);

        Auth::login($user);

        // Redirect to the next step: company form
        return redirect()->route('entreprise.create')->with('success', 'User data saved.');
    }
}
