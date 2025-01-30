<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\Entreprise;
use App\Models\Activite;
use App\Models\User;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;

class entrepriseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Illuminate\View\View
     */
    public function index() : View
    {
        return view('entreprise.index', [
            'entreprises' => Entreprise::simplePaginate(9)
        ]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return Illuminate\View\View
     */
    public function indexUser()
    {
        $userId = Auth::user()->id;

        // Vérification des statuts
        $isAdmin = Auth::user()->travailler_entreprises()->wherePivot('statut', 'Admin')->exists();
        $isEmploye = Auth::user()->travailler_entreprises()->wherePivot('statut', 'Employé')->exists();
        $isInvite = Auth::user()->travailler_entreprises()->wherePivot('statut', 'Invité')->exists();
        $isCreator = Entreprise::where('idCreateur', $userId)->exists();

        // Récupérer les IDs des entreprises selon les statuts
        $adminEntrepriseIds = Auth::user()->travailler_entreprises()->wherePivot('statut', 'Admin')->pluck('idEntreprise')->toArray();
        $employeEntrepriseIds = Auth::user()->travailler_entreprises()->wherePivot('statut', 'Employé')->pluck('idEntreprise')->toArray();
        $inviteEntrepriseIds = Auth::user()->travailler_entreprises()->wherePivot('statut', 'Invité')->pluck('idEntreprise')->toArray();

        // Récupération des entreprises correspondantes
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
     * Display the specified resource.
     *
     * @param  Entreprise $entreprise
     * @return Illuminate\View\View
     */
    public function show(Entreprise $entreprise)
    {
        if(Auth::user()->travailler_entreprises()->wherePivot('statut', 'Admin')->wherePivot('idEntreprise',$entreprise->id)->count() > 0 || Auth::user()->travailler_entreprises()->wherePivot('statut', 'Employé')->wherePivot('idEntreprise',$entreprise->id)->count() > 0 || Auth::user()->travailler_entreprises()->wherePivot('statut', 'Invité')->wherePivot('idEntreprise',$entreprise->id)->count() > 0) {
            return view('entreprise.show', [
                'entreprise' => $entreprise
            ]);
        }
        else {
            return redirect()->route('entreprise.indexUser');
        }
    }

    /**
     * Fonction pour voir les activités et réserver
     * 
     * @return Illuminate\View\View
     */
    public function showActivites(Entreprise $entreprise)
    {
        $services = Activite::where('idEntreprise', $entreprise->id)->get();
        return view('activite.show', ['entreprise' => $entreprise], compact('services'));
    }

    /**
     * Méthode ajax pour ajouter, modifier, mettre à jour ou supprimer un employé
     *
     * @return response()
     */
    public function ajax(Request $request)
    {
        switch ($request->type) {
           case 'invite':
            foreach /* ($activitesEntreprise */($request->activites as $idActivite) {
                $event = User::where('email',$request->email)->first()->travailler_entreprises()->attach($request->idEntreprise, ['idActivite' => $idActivite,'statut' => 'Invité']);  /* à modifier mettre activité récupérée de $request */
            }

                $event = User::where('email',$request->email)->first();

              return response()->json($event);
             break;
  
           case 'upgrade':
                $event = User::where('id',$request->idEmploye)->first()->travailler_entreprises->where('id', $request->idEntreprise)->first()->pivot->update([
                'statut' => 'Admin',
              ]);
 
              return response()->json($event);
             break;
            
            case 'downgrade':
                $event = User::where('id',$request->idEmploye)->first()->travailler_entreprises->where('id', $request->idEntreprise)->first()->pivot->update([
                    'statut' => 'Employé',
                  ]);

                return response()->json($event);
                break;
  
           case 'delete':
              $activitesEntreprise = Entreprise::where('id', $request->idEntreprise)->first()->activites()->get('id');

              foreach ($activitesEntreprise as $idActivite) {
                $event = User::where('id',$request->idEmploye)->first()->travailler_activites()->wherePivot('idEntreprise',$request->idEntreprise)->detach($idActivite->id);
              }

              //$event = User::where('id',$request->idEmploye)->first()->travailler_entreprises->where('id', $request->idEntreprise)->first()->pivot->delete();
  
              return response()->json($event);
             break;
             
           default:
             # code...
             break;
        }
    }

    /**
     * Show the form for creating a new resource.
     * 
     * @return Illuminate\View\View
     */
    public function create() : View
    {
        return view('entreprise.create');
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
                'numTel' => ['required', 'string', 'max:15', 'regex:/^(\d{2} \d{2} \d{2} \d{2} \d{2}|\d{10})$/'], 
                'email' => ['required', 'email', 'unique:entreprises,email', 'max:255'],
                'rue' => ['required', 'string', 'max:255'],
                'codePostal' => ['required', 'string', 'max:6', 'regex:/^\d{5}|\d{2} \d{3}$/'], 
                'ville' => ['required', 'string', 'max:255'],
            ]);

            // Stocker les données en session
            session(['company' => $validated]);

            // Rediriger vers le formulaire type de rendez-vous
            return view('entreprise.typeRdv');
        }

        // Afficher le formulaire entreprise
        return view('entreprise.typeRdv');
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
        //$user = session('user', []);
        $company = session('company', []);
        $appointment = session('appointment', []);

        // Vérifier que toutes les informations nécessaires sont disponibles
        if (empty($company) || empty($appointment)) {
            return redirect()->route('entreprise.create')
                             ->with('error', 'Veuillez compléter toutes les étapes avant de visualiser le récapitulatif.');
        }

        // Afficher la page récapitulative avec toutes les données
        return view('entreprise.recap', compact('company', 'appointment'));
    }

    /**
     * Store appointment responses in the session and return a success response.
     * 
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function storeAppointments(Request $request)
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
    public function store() 
    {
        $company = session('company');
        $appointment = session('appointment');

        // Vérif pour améliorer
        if (empty($company) || empty($appointment)) {
            throw \Illuminate\Validation\ValidationException::withMessages([
                'error' => 'Données d\'inscription incomplètes. Veuillez recommencer le processus.',
            ]);
        }
        
        $newCompany = Entreprise::create([
            'libelle' => $company['nomEntreprise'],
            'siren' => $company['siren'],
            'adresse' => $company['rue'] . ', ' . $company['codePostal'] . ' ' . $company['ville'],
            'numTel' => $company['numTel'],
            'email' => $company['email'],
            'typeRdv' => json_encode(array_values($appointment)),
            'idCreateur' => Auth::id()
        ]);
        
        session()->forget(['company', 'appointment']);

        return redirect()->route('entreprise.services.index', ['entreprise' => $newCompany->id])->with('success', 'Inscription réussie.'); 
    }
}
