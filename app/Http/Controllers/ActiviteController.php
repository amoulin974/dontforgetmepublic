<?php
/**
 * @file ActiviteController.php
 * @brief Controller class for managing activities.
 */
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Activite;
use App\Models\Entreprise;
use App\Models\Plage;
use App\Models\User;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;

/**
 * @class ActiviteController
 * @brief Controller class for managing activities.
 * 
 * This controller is responsible for displaying, creating, editing, updating, and deleting activities,
 * as well as managing their associations with companies, users, and schedules.
 */
class ActiviteController extends Controller
{
    /**
     * Display a listing of the activities for the specified company.
     *
     * This method checks if the current user is allowed to view the activities
     * based on their role (Admin, Employee, or Creator) within the company.
     *
     * @param Entreprise $entreprise The company instance.
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse Returns the view with the list of activities
     *         or redirects to the company index if unauthorized.
     */
    public function index(Entreprise $entreprise)
    {
        $isAdmin = Auth::user()->travailler_entreprises()
                ->wherePivot('statut', 'Admin')
                ->wherePivot('idEntreprise', $entreprise->id)
                ->count() > 0;

        $isEmploye = Auth::user()->travailler_entreprises()
                ->wherePivot('statut', 'Employé')
                ->wherePivot('idEntreprise', $entreprise->id)
                ->count() > 0;

        $isCreator = $entreprise->idCreateur == Auth::user()->id;

        $isAllow = $isAdmin || $isCreator || $isEmploye;

        if ($isAllow) {
            $services = Activite::where('idEntreprise', $entreprise->id)->get();
            return view('activite.index', ['entreprise' => $entreprise], compact('services'));
        } else {
            return redirect()->route('entreprise.index');
        }
    }

    /**
     * Show the form for creating a new activity.
     *
     * Only users with Admin or Creator privileges for the company are allowed.
     *
     * @param Entreprise $entreprise The company instance.
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse Returns the view for creating a new activity
     *         or redirects to the company index if unauthorized.
     */
    public function create(Entreprise $entreprise)
    {
        $isAdmin = Auth::user()->travailler_entreprises()
                ->wherePivot('statut', 'Admin')
                ->wherePivot('idEntreprise', $entreprise->id)
                ->count() > 0;

        $isCreator = $entreprise->idCreateur == Auth::user()->id;

        $isAllow = $isAdmin || $isCreator;
        if (!$isAllow) {
            return redirect()->route('entreprise.index');
        } else {
            return view('activite.create', ['entreprise' => $entreprise]);
        }
    }

    /**
     * Store a newly created activity in storage.
     *
     * This method validates the input, converts the duration from minutes to a time format,
     * creates the activity, and assigns users (Admin, Employees) to it.
     *
     * @param Request $request The HTTP request instance.
     * @param Entreprise $entreprise The company instance.
     * @return \Illuminate\Http\RedirectResponse Redirects to the activity listing with a success message.
     */
    public function store(Request $request, Entreprise $entreprise)
    {
        $isAdmin = Auth::user()->travailler_entreprises()
                ->wherePivot('statut', 'Admin')
                ->wherePivot('idEntreprise', $entreprise->id)
                ->count() > 0;

        $isCreator = $entreprise->idCreateur == Auth::user()->id;

        $isAllow = $isAdmin || $isCreator;

        if (!$isAllow) {
            return redirect()->route('entreprise.index');
        }
        else{
            $request->validate([
                'libelle' => 'required|string|max:255',
                'duree' => 'required|date_format:H:i', 
                'nbrPlaces' => 'required|integer|min:1'
            ]);

            list($heures, $minutes) = explode(':', $request->duree);
            $dureeEnMinutes = ($heures * 60) + $minutes;

            $dureeInTimeFormat = gmdate('H:i:s', $dureeEnMinutes * 60);

            $activite = Activite::create([
                'libelle' => $request->libelle,
                'duree' => $dureeInTimeFormat,
                'nbrPlaces' => $request->nbrPlaces,
                'idEntreprise' => $entreprise->id
            ]);

            $activite->travailler_users()->attach(auth()->id(), [
                'idEntreprise' => $entreprise->id,
                'statut'       => 'Admin',
            ]);

            $entreprise->travailler_users()->where('statut', 'Employé')->get()->each(function ($user) use ($activite, $entreprise) {
                $activite->travailler_users()->syncWithoutDetaching([$user->id => [
                    'idEntreprise' => $entreprise->id,
                    'statut'       => 'Employé',
                ]]);
            });
            $entreprise->travailler_users()->where('statut', 'Admin')->get()->each(function ($user) use ($activite, $entreprise) {
                if ($user->id != Auth::user()->id) {
                    $activite->travailler_users()->attach($user->id, [
                        'idEntreprise' => $entreprise->id,
                        'statut' => 'Admin',
                    ]);
                }
            });

          if ($entreprise->activites()->count() === 1) {
              $entreprise->update(['publier' => 1]);
          }
    
            return redirect()->route('entreprise.services.index', ['entreprise' => $entreprise->id])->with('success', 'Service créé avec succès.');
        }
    } 

    /**
     * Show the form for editing the specified activity.
     *
     * Only users with Admin or Creator privileges are allowed to edit.
     *
     * @param Entreprise $entreprise The company instance.
     * @param int $id The ID of the activity to edit.
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse Returns the view for editing the activity
     *         or redirects to the company index if unauthorized.
     */
    public function edit(Entreprise $entreprise, $id)
    {
        $isAdmin = Auth::user()->travailler_entreprises()
                ->wherePivot('statut', 'Admin')
                ->wherePivot('idEntreprise', $entreprise->id)
                ->count() > 0;

        $isCreator = $entreprise->idCreateur == Auth::user()->id;

        $isAllow = $isAdmin || $isCreator;
        if (!$isAllow) {
            return redirect()->route('entreprise.index');
        } else {
            $service = Activite::where('id', $id)
                ->where('idEntreprise', $entreprise->id)
                ->firstOrFail();
            return view('activite.edit', ['entreprise' => $entreprise, 'service' => $service]);
        }
    }

    /**
     * Update the specified activity in storage.
     *
     * Validates the input, converts the duration, and updates the activity.
     *
     * @param Request $request The HTTP request instance.
     * @param Entreprise $entreprise The company instance.
     * @param int $id The ID of the activity to update.
     * @return \Illuminate\Http\RedirectResponse Redirects to the activity listing with a success message.
     */
    public function update(Request $request, Entreprise $entreprise, $id)
    {
        $isAdmin = Auth::user()->travailler_entreprises()
            ->wherePivot('statut', 'Admin')
            ->wherePivot('idEntreprise', $entreprise->id)
            ->count() > 0;
        $isCreator = $entreprise->idCreateur == Auth::user()->id;

        $isAllow = $isAdmin || $isCreator;
        if (!$isAllow) {
            return redirect()->route('entreprise.index');
        } else {
            $request->validate([
                'libelle' => 'required|string|max:255',
                'duree' => 'required|date_format:H:i', 
                'nbrPlaces' => [
                    'required',
                    'integer',
                    'min:1',
                    'max:' . $entreprise->capaciteMax, 
                ]
            ]);

            $service = Activite::findOrFail($id);
    
            list($heures, $minutes) = explode(':', $request->duree);
            $dureeEnMinutes = ($heures * 60) + $minutes;

            $dureeInTimeFormat = gmdate('H:i:s', $dureeEnMinutes * 60);
    
            $service->update([
                'libelle' => $request->libelle,
                'duree' => $dureeInTimeFormat,
                'nbrPlaces' => $request->nbrPlaces
            ]);

            return redirect()->route('entreprise.services.index', ['entreprise' => $entreprise->id])
                ->with('success', 'Service mis à jour avec succès.');
        }
    }

    /**
     * Remove the specified activity from storage.
     *
     * Detaches all related users, time slots, and company associations before deleting.
     *
     * @param Entreprise $entreprise The company instance.
     * @param int $id The ID of the activity to delete.
     * @return \Illuminate\Http\RedirectResponse Redirects to the activity listing with a success message.
     */
    public function destroy(Entreprise $entreprise, $id)
    {
        $isAdmin = Auth::user()->travailler_entreprises()
                ->wherePivot('statut', 'Admin')
                ->wherePivot('idEntreprise', $entreprise->id)
                ->count() > 0;

        $isCreator = $entreprise->idCreateur == Auth::user()->id;

        $isAllow = $isAdmin || $isCreator;
        if (!$isAllow) {
            return redirect()->route('entreprise.index');
        } else {
            $service = Activite::findOrFail($id);
            $service->travailler_users()->detach();
            $service->plages()->detach();
            $service->travailler_entreprises()->detach();
            $service->delete();

            if ($entreprise->activites()->count() === 0) {
                $entreprise->update(['publier' => 0]);
            }

            return redirect()->route('entreprise.services.index', ['entreprise' => $entreprise->id])
                ->with('success', 'Service deleted successfully.');
        }
    }

    /**
     * Display or return time slots (plages) for a specified activity.
     *
     * This method handles both AJAX and regular HTTP requests. For AJAX requests,
     * it returns a JSON response containing the time slots for the activity.
     * Otherwise, it returns the view for creating a new time slot.
     *
     * @param Request $request The HTTP request instance.
     * @param Entreprise $entreprise The company instance.
     * @param int $id The ID of the activity.
     * @return \Illuminate\Http\JsonResponse|\Illuminate\View\View Returns a JSON response or a view.
     */
    public function createPlage(Request $request, Entreprise $entreprise, User $employe)
    {
        if($request->ajax()) {
            if(Auth::user()->travailler_entreprises->where('id', $entreprise->id)->first()->pivot->statut != 'Invité') {
                $plages = User::where('id', $employe->id)->first()->plages()->where('entreprise_id', $entreprise->id)->get();
                if ($plages) {
                    foreach ($plages as $plage) {
                        $plage->activites = $plage->activites()->get();
                    }
                    return response()->json($plages);
                } else {
                    return response()->json(['error' => 'Plages not found'], 404);
                }
            }
            else {
                return view('plage.create', ['entreprise' => $entreprise, 'employe' => $employe]);
            }
        }
        return view('plage.create', ['entreprise' => $entreprise, 'employe' => $employe]);
    }

    /**
     * Handle AJAX requests for time slot (plage) operations.
     *
     * Processes various operations on time slots including adding, updating, deleting,
     * and modifying employee assignments.
     *
     * @param Request $request The HTTP request instance.
     * @param Entreprise $entreprise The company instance.
     * @param int $id The ID of the activity.
     * @return \Illuminate\Http\JsonResponse Returns a JSON response with the result of the operation.
     */
    public function ajaxPlage(Request $request, Entreprise $entreprise, User $employe)
    {
        switch ($request->type) {
            case 'add':
               if (!$request->interval){
                 $event = Plage::create([
                     'heureDeb' => $request->heureDeb,
                     'heureFin' => $request->heureFin,
                     'datePlage' => $request->datePlage,
                     'interval' => '00:05:00',
                     'planTables' => json_encode(['UnTest']),
                     'entreprise_id' => $entreprise->id,
                 ]);
               }
               else {
                 $event = Plage::create([
                     'heureDeb' => $request->heureDeb,
                     'heureFin' => $request->heureFin,
                     'datePlage' => $request->datePlage,
                     'interval' => $request->interval,
                     'planTables' => json_encode(['UnPlanDeTables']),
                     'entreprise_id' => $entreprise->id,
                 ]);
               }

               $event->employes()->attach($employe->id);

               foreach($request->activites_affecter as $id){
                    $event->activites()->attach($id);
                }

                $event->activites = $event->activites()->get();
               
               return response()->json($event);
              break;
   
            case 'update':
               $event = Plage::where("id",$request->id)->first()->update([
                 'heureDeb' => $request->heureDeb,
                 'heureFin' => $request->heureFin,
                 'datePlage' => $request->datePlage,
               ]);
  
               return response()->json($event);
              break;
   
            case 'delete':
                $plage = Plage::where("id",$request->id)->first();
                $plage->employes()->detach();
                $plage->activites()->detach();
                $event = $plage->delete();

                return response()->json($event);
                break;

            case 'modify':
               $event = Plage::where("id",$request->id)->first();
                $event->activites()->detach();
                foreach($request->activites_affecter as $id){
                    $event->activites()->attach($id);
                }
                $event->activites = $event->activites()->get();

               return response()->json($event);
              break;
              
            default:
                break;
        }
    }
}
