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

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
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
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\User
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
     * Show the page for the choice of the type of account (user or company).
     * 
     * @return \Illuminate\View\View
     */
    public function showChoicePage() {
        return view('auth.choose-account-type');
    }

    /**
     * Show the user registration page.
     * 
     * @return \Illuminate\View\View
     */
    public function showUserRegisterPage() {
        return view('auth.user-register');
    }

    /**
     * Handle the user registration form submission and redirect to the company registration page.
     * 
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\View\View
     */
    public function showCompanyRegisterPage(Request $request)
    {
        if ($request->isMethod('post')) {
            // Valider les données Utilisateur
            $validated = $request->validate([
                'nom' => ['required', 'string', 'max:255'],
                'prenom' => ['required', 'string', 'max:255'],
                'numTel' => ['nullable', 'string', 'max:15', 'regex:/^(\d{2} \d{2} \d{2} \d{2} \d{2}|\d{10})$/'],
                'email' => ['required', 'email', 'unique:users,email', 'max:255'],
                'password' => ['required', 'string', 'min:8', 'confirmed'],
            ]);
    
            // Stocker les données utilisateur dans la session
            session(['user' => $validated]);
    
            // Rediriger vers l'étape suivante : formulaire entreprise
            return view('auth.company-register');
        }
    
        // Afficher le formulaire pour l'étape utilisateur
        return view('auth.company-register');
    }

    /**
     * Handle the company registration form submission and redirect to the appointment type page.
     * 
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\View\View
     */
    public function showTypeRdvPage(Request $request)
    {
        if ($request->isMethod('post')) {
            // Valider les données Entreprise
            $validated = $request->validate([
                'nomEntreprise' => ['required', 'string', 'max:255'],
                'siren' => ['required', 'string', 'max:14', 'unique:entreprises,siren', 'regex:/^(\d{9}|\d{3} \d{3} \d{3})$/'], 
                'numTel' => ['required', 'string', 'max:15', 'regex:/^(\d{2} \d{2} \d{2} \d{2} \d{2}|\d{10})$/'], // Format : ** ** ** ** ** ou **********
                'email' => ['required', 'email', 'unique:entreprises,email', 'max:255'],
                'rue' => ['required', 'string', 'max:255'],
                'codePostal' => ['required', 'string', 'max:5', 'regex:/^\d{5}$/'], 
                'ville' => ['required', 'string', 'max:255'],
            ]);

            // Stocker les données en session
            session(['company' => $validated]);

            // Rediriger vers le formulaire type de rendez-vous
            return view('auth.company-type-rdv');
        }

        // Afficher le formulaire entreprise
        return view('auth.company-register');
    }

    /**
     * Show the recap page with user, company, and appointment data.
     * Redirect if required data is missing.
     * 
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function showRecapPage(Request $request)
    {
        // Récupérer les données utilisateur et entreprise depuis la session
        $user = session('user', []);
        $company = session('company', []);
        $appointment = session('appointment', []);

        // Vérifier que toutes les informations nécessaires sont disponibles
        if (empty($user) || empty($company) || empty($appointment)) {
            return redirect()->route('register.company.register.user')
                             ->with('error', 'Veuillez compléter toutes les étapes avant de visualiser le récapitulatif.');
        }

        // Afficher la page récapitulative avec toutes les données
        return view('auth.company-recap', compact('user', 'company', 'appointment'));
    }

    /**
     * Store appointment responses in the session and return a success response.
     * 
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function storeResponses(Request $request)
    {
        // Validez les données envoyées
        $validated = $request->validate([
            '*' => 'required|string|max:255', 
        ]);

        session(['appointment' => $validated]);

        // Réponse de succès
        return response()->json([
            'message' => 'Réponses enregistrées avec succès.',
            'responses' => $validated,
        ], 200);
    }

    /**
     * Finalize the registration process by saving the user, company, and appointment data.
     * Log in the user and clear the session after registration.
     * 
     * @return \Illuminate\Http\RedirectResponse
     */
    public function submit() 
    {
        $user = session('user');
        $company = session('company');
        $appointment = session('appointment');

        // Vérif pour améliorer
        if (empty($user) || empty($company) || empty($appointment)) {
            throw \Illuminate\Validation\ValidationException::withMessages([
                'error' => 'Données d\'inscription incomplètes. Veuillez recommencer le processus.',
            ]);
        }

        $newUser = $this->create($user); 

        Auth::login($newUser);
        
        $newCompany = Entreprise::create([
            'libelle' => $company['nomEntreprise'],
            'siren' => $company['siren'],
            'adresse' => $company['rue'] . ', ' . $company['codePostal'] . ' ' . $company['ville'],
            'numTel' => $company['numTel'],
            'email' => $company['email'],
            'typeRdv' => json_encode(array_values($appointment)),
            'idCreateur' => $newUser->id
        ]);
        
        session()->forget(['user', 'company', 'appointment']);

        return redirect()->route('entreprise.services.index', ['entreprise' => $newCompany->id])->with('success', 'Inscription réussie.'); 
    }
}
