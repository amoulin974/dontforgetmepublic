<?php
/**
 * @file EntrepriseController.php
 * @brief Controller class for managing enterprises.
 */
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\Entreprise;
use App\Models\Activite;
use App\Models\User;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;

/**
 * @class EntrepriseController
 * @brief Controller class for managing enterprises.
 *
 * This controller handles listing, displaying, creating, updating, and managing
 * various aspects of enterprises including their activities, user roles, and registration.
 */
class EntrepriseController extends Controller
{
    /**
     * Display a paginated list of enterprises.
     *
     * This method retrieves enterprises with a simple pagination (9 per page)
     * and returns the enterprise index view.
     *
     * @return \Illuminate\View\View The view displaying the list of enterprises.
     */
    public function index() : View
    {
        return view('entreprise.index', [
            'entreprises' => Entreprise::simplePaginate(9)
        ]);
    }

    /**
     * Display a paginated list of enterprises associated with the authenticated user.
     *
     * The method checks the user roles (Admin, Employee, or Invite) and retrieves the corresponding
     * enterprise IDs. It also includes enterprises created by the user.
     *
     * @return \Illuminate\View\View The view displaying the list of enterprises for the user.
     */
    public function indexUser()
    {
        $userId = Auth::user()->id;

        $isAdmin    = Auth::user()->travailler_entreprises()->wherePivot('statut', 'Admin')->exists();
        $isEmploye  = Auth::user()->travailler_entreprises()->wherePivot('statut', 'Employé')->exists();
        $isInvite   = Auth::user()->travailler_entreprises()->wherePivot('statut', 'Invité')->exists();
        $isCreator  = Entreprise::where('idCreateur', $userId)->exists();

        $adminEntrepriseIds   = Auth::user()->travailler_entreprises()->wherePivot('statut', 'Admin')->pluck('idEntreprise')->toArray();
        $employeEntrepriseIds = Auth::user()->travailler_entreprises()->wherePivot('statut', 'Employé')->pluck('idEntreprise')->toArray();
        $inviteEntrepriseIds  = Auth::user()->travailler_entreprises()->wherePivot('statut', 'Invité')->pluck('idEntreprise')->toArray();

        $entreprises = Entreprise::query()
            ->where('idCreateur', $userId)
            ->orWhereIn('id', $adminEntrepriseIds)
            ->orWhereIn('id', $employeEntrepriseIds)
            ->orWhereIn('id', $inviteEntrepriseIds)
            ->distinct()
            ->simplePaginate(9);

        return view('entreprise.index', compact('entreprises'));
    }

    /**
     * Display the specified enterprise.
     *
     * The method checks if the authenticated user has an associated role (Admin, Employee, or Invite)
     * for the enterprise. If authorized, it returns the enterprise detail view; otherwise, it redirects
     * to the user enterprise index.
     *
     * @param Entreprise $entreprise The enterprise instance.
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse The enterprise detail view or a redirect.
     */
    public function show(Entreprise $entreprise)
    {
        if (
            Auth::user()->travailler_entreprises()->wherePivot('statut', 'Admin')->wherePivot('idEntreprise', $entreprise->id)->count() > 0 ||
            Auth::user()->travailler_entreprises()->wherePivot('statut', 'Employé')->wherePivot('idEntreprise', $entreprise->id)->count() > 0 ||
            Auth::user()->travailler_entreprises()->wherePivot('statut', 'Invité')->wherePivot('idEntreprise', $entreprise->id)->count() > 0
        ) {
            return view('entreprise.show', [
                'entreprise' => $entreprise
            ]);
        } else {
            return redirect()->route('entreprise.indexUser');
        }
    }

    /**
     * Display the activities for a given enterprise.
     *
     * Retrieves all activities associated with the specified enterprise and returns the view for
     * showing activities and making reservations.
     *
     * @param Entreprise $entreprise The enterprise instance.
     * @return \Illuminate\View\View The view displaying the activities for the enterprise.
     */
    public function showActivites(Entreprise $entreprise)
    {
        $services = Activite::where('idEntreprise', $entreprise->id)->get();
        return view('activite.show', ['entreprise' => $entreprise], compact('services'));
    }

    /**
     * Handle AJAX requests for managing enterprise user roles.
     *
     * This method processes different operations based on the "type" parameter in the request:
     * - **invite**: Invites a user to an enterprise for specified activities.
     * - **upgrade**: Upgrades a user's role to Admin for an enterprise.
     * - **downgrade**: Downgrades a user's role to Employee for an enterprise.
     * - **delete**: Removes a user's association with an enterprise's activities.
     *
     * @param Request $request The HTTP request containing the operation type and related data.
     * @return \Illuminate\Http\JsonResponse A JSON response containing the result of the operation.
     */
    public function ajax(Request $request)
    {
        switch ($request->type) {
            case 'invite':
                foreach ($request->activites as $idActivite) {
                    User::where('email', $request->email)
                        ->first()
                        ->travailler_entreprises()
                        ->attach($request->idEntreprise, [
                            'idActivite' => $idActivite,
                            'statut'     => 'Invité'
                        ]);
                }
                $event = User::where('email', $request->email)->first();
                return response()->json($event);
                break;

            case 'upgrade':
                $event = User::where('id', $request->idEmploye)
                    ->first()
                    ->travailler_entreprises
                    ->where('id', $request->idEntreprise)
                    ->first()
                    ->pivot
                    ->update([
                        'statut' => 'Admin',
                    ]);
                return response()->json($event);
                break;

            case 'downgrade':
                $event = User::where('id', $request->idEmploye)
                    ->first()
                    ->travailler_entreprises
                    ->where('id', $request->idEntreprise)
                    ->first()
                    ->pivot
                    ->update([
                        'statut' => 'Employé',
                    ]);
                return response()->json($event);
                break;

            case 'delete':
                $activitesEntreprise = Entreprise::where('id', $request->idEntreprise)
                    ->first()
                    ->activites()
                    ->get('id');

                foreach ($activitesEntreprise as $idActivite) {
                    $event = User::where('id', $request->idEmploye)
                        ->first()
                        ->travailler_activites()
                        ->wherePivot('idEntreprise', $request->idEntreprise)
                        ->detach($idActivite->id);
                }
                return response()->json($event);
                break;

            default:
                break;
        }
    }

    /**
     * Show the form for creating a new enterprise.
     *
     * @return \Illuminate\View\View The view for creating a new enterprise.
     */
    public function create() : View
    {
        return view('entreprise.create');
    }

    /**
     * Handle the enterprise registration form submission and show the appointment type page.
     *
     * If the HTTP method is POST, the method validates the enterprise data, stores it in the session,
     * and then displays the appointment type form.
     *
     * @param Request $request The HTTP request instance.
     * @return \Illuminate\View\View The view for selecting the appointment type.
     */
    public function showTypeRdvPage(Request $request)
    {
        if ($request->isMethod('post')) {
            $validated = $request->validate([
                'nomEntreprise' => ['required', 'string', 'max:255'],
                'siren'         => ['required', 'string', 'max:14', 'unique:entreprises,siren', 'regex:/^(\d{9}|\d{3} \d{3} \d{3})$/'],
                'metier'        => ['required', 'string', 'in:Restaurant,Coiffeur,Avocat,Auto-école,Autres'],
                'numTel'        => ['required', 'string', 'max:15', 'regex:/^(\d{2} \d{2} \d{2} \d{2} \d{2}|\d{10})$/'],
                'email'         => ['required', 'email', 'unique:entreprises,email', 'max:255'],
                'rue'           => ['required', 'string', 'max:255'],
                'codePostal'    => ['required', 'string', 'max:6', 'regex:/^\d{5}|\d{2} \d{3}$/'],
                'ville'         => ['required', 'string', 'max:255'],
                'description'   => ['nullable', 'string', 'max:255'],
            ]);

            session(['company' => $validated]);

            return view('entreprise.typeRdv');
        }

        return view('entreprise.typeRdv');
    }

    /**
     * Show the recap page with enterprise registration and appointment data.
     *
     * This method retrieves the company and appointment data from the session and, if complete,
     * shows a recap view. If any required data is missing, it redirects back to the enterprise creation form.
     *
     * @param Request $request The HTTP request instance.
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse The recap view or a redirect if data is missing.
     */
    public function showRecapPage(Request $request)
    {
        $company     = session('company', []);
        $appointment = session('appointment', []);
        $capacity    = session('capacity', []);

        if (empty($company) || empty($appointment)) {
            return redirect()->route('entreprise.create')
                ->with('error', 'Veuillez compléter toutes les étapes avant de visualiser le récapitulatif.');
        }

        return view('entreprise.recap', compact('company', 'appointment', 'capacity'));
    }

    /**
     * Store appointment responses in the session.
     *
     * Validates the appointment data and stores it in the session for later use.
     *
     * @param Request $request The HTTP request containing appointment responses.
     * @return \Illuminate\Http\JsonResponse JSON response indicating success and echoing the stored responses.
     */
    public function storeAppointments(Request $request)
    {
        $responses = $request->except('capacity'); 
        $capacity = $request->input('capacity'); 

        $validatedResponses = validator($responses, [
            '*' => 'required|string|max:255',
        ])->validate();
    
        $validatedCapacity = validator(['capacity' => $capacity], [
            'capacity' => 'nullable|integer|min:1'
        ])->validate();
    
        session([
            'appointment' => $validatedResponses,
            'capacity' => $validatedCapacity['capacity'] ?? null 
        ]);
    
        return response()->json([
            'message' => 'Réponses enregistrées avec succès.',
            'responses' => $validatedResponses,
            'capacity' => $validatedCapacity['capacity'] ?? null,
        ], 200);
    }

    /**
     * Finalize the enterprise registration process.
     *
     * This method saves the enterprise data and appointment responses from the session into the database,
     * creates a new enterprise, clears the session data, and redirects to the enterprise services page.
     *
     * @return \Illuminate\Http\RedirectResponse Redirects to the enterprise services index with a success message.
     *
     * @throws \Illuminate\Validation\ValidationException If the registration data is incomplete.
     */
    public function store()
    {
        $company     = session('company');
        $appointment = session('appointment');
        $capacity = session('capacity');

        if (empty($company) || empty($appointment)) {
            throw \Illuminate\Validation\ValidationException::withMessages([
                'error' => 'Données d\'inscription incomplètes. Veuillez recommencer le processus.',
            ]);
        }

        $newCompany = Entreprise::create([
            'libelle'    => $company['nomEntreprise'],
            'siren'      => $company['siren'],
            'metier'     => $company['metier'],
            'adresse'    => $company['rue'] . ', ' . $company['codePostal'] . ' ' . $company['ville'],
            'description' => $company['description'] ?? 'Aucune description renseignée pour le moment.',
            'numTel'     => $company['numTel'],
            'email'      => $company['email'],
            'typeRdv'    => json_encode(array_values($appointment)),
            'capaciteMax' => $capacity,
            'idCreateur' => Auth::id()
        ]);

        session()->forget(['company', 'appointment', 'capacity']);

        return redirect()->route('entreprise.services.index', ['entreprise' => $newCompany->id])
            ->with('success', 'Inscription réussie.');
    }

    /**
     * Show the form for editing an existing enterprise.
     *
     * @param Entreprise $entreprise The enterprise instance to edit.
     * @return \Illuminate\View\View The view for editing the enterprise.
     */
    public function edit(Entreprise $entreprise) : View
    {
        return view('entreprise.edit', [
            'entreprise' => $entreprise
        ]);
    }

    /**
     * Update the specified enterprise in storage.
     *
     * Validates the input data, updates the enterprise record, and also updates the
     * appointment type responses (stored as JSON) based on the input.
     *
     * @param Request $request The HTTP request object.
     * @param Entreprise $entreprise The enterprise instance to update.
     * @return \Illuminate\Http\RedirectResponse Redirects to the enterprise show page with a success message.
     */
    public function update(Request $request, Entreprise $entreprise)
    {
        $validated = $request->validate([
            'libelle'     => ['required', 'string', 'max:255'],
            'siren'       => ['required', 'string', 'max:14', 'regex:/^(\d{9}|\d{3} \d{3} \d{3})$/'],
            'metier' => ['required', 'string', 'in:Restaurant,Coiffeur,Avocat,Autres,Auto-école'],
            'rue'         => ['required', 'string', 'max:255'],
            'codePostal'  => ['required', 'string', 'max:6', 'regex:/^\d{5}|\d{2} \d{3}$/'],
            'ville'       => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:255'],
            'email'       => ['required', 'email', 'max:255'],
            'numTel'      => ['required', 'string', 'max:15', 'regex:/^(\d{2} \d{2} \d{2} \d{2} \d{2}|\d{10})$/'],
            'capaciteMax' => ['nullable', 'integer', 'min:1'],
        ]);

        $entreprise->libelle   = $validated['libelle'];
        $entreprise->siren     = $validated['siren'];
        $entreprise->metier    = $validated['metier'];
        $entreprise->adresse   = $validated['rue'] . ', ' . $validated['codePostal'] . ' ' . $validated['ville'];
        $entreprise->description = $validated['description'];
        $entreprise->email     = $validated['email'];
        $entreprise->numTel    = $validated['numTel'];

        $typeRdv = $entreprise->typeRdv ? json_decode($entreprise->typeRdv, true) : [];
        $typeRdv[0] = $request->input('question_0', $typeRdv[0] ?? 0);
        $typeRdv[1] = $request->input('question_1', $typeRdv[1] ?? 0);
        $typeRdv[2] = $request->input('question_2', $typeRdv[2] ?? 0);
        $typeRdv[3] = $request->input('question_3', $typeRdv[3] ?? 0);

        $entreprise->typeRdv = json_encode($typeRdv);
        $entreprise->capaciteMax = ($typeRdv[0] == 0) ? 1 : $validated['capaciteMax'];

        $entreprise->save();

        return redirect()->route('entreprise.show', $entreprise)
            ->with('success', 'Entreprise mise à jour avec succès.');
    }
}


