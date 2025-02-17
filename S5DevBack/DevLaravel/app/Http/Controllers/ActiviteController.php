<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Activite;
use App\Models\Entreprise;
use App\Models\Plage;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;

/**
 * @brief Controller class for managing activities.
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
        // Check if the user is an Admin for the company.
        $isAdmin = Auth::user()->travailler_entreprises()
                ->wherePivot('statut', 'Admin')
                ->wherePivot('idEntreprise', $entreprise->id)
                ->count() > 0;

        // Check if the user is an Employee for the company.
        $isEmploye = Auth::user()->travailler_entreprises()
                ->wherePivot('statut', 'Employé')
                ->wherePivot('idEntreprise', $entreprise->id)
                ->count() > 0;

        // Check if the user is the creator of the company.
        $isCreator = $entreprise->idCreateur == Auth::user()->id;

        // Determine if the user is allowed to view the activities.
        $isAllow = $isAdmin || $isCreator || $isEmploye;

        if ($isAllow) {
            // Retrieve all activities linked to the company.
            $services = Activite::where('idEntreprise', $entreprise->id)->get();
            return view('activite.index', ['entreprise' => $entreprise], compact('services'));
        } else {
            // Redirect to the company index if unauthorized.
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
        // Check if the user is an Admin for the company.
        $isAdmin = Auth::user()->travailler_entreprises()
                ->wherePivot('statut', 'Admin')
                ->wherePivot('idEntreprise', $entreprise->id)
                ->count() > 0;

        // Check if the user is the creator of the company.
        $isCreator = $entreprise->idCreateur == Auth::user()->id;

        // Allow creation only if the user is an Admin or the Creator.
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
        // Check if the user is an Admin.
        $isAdmin = Auth::user()->travailler_entreprises()
                ->wherePivot('statut', 'Admin')
                ->wherePivot('idEntreprise', $entreprise->id)
                ->count() > 0;

        // Check if the user is the creator of the company.
        $isCreator = $entreprise->idCreateur == Auth::user()->id;

        $isAllow = $isAdmin || $isCreator;

        if (!$isAllow) {
            return redirect()->route('entreprise.index');
        }
        else{
            $request->validate([
              'libelle' => 'required|string|max:255',
              'duree' => 'required|integer|min:1',
              'nbrPlaces' => 'required|integer|min:1'
          ]);

          $dureeInTimeFormat = gmdate('H:i:s', $request->duree * 60);

          $activite = Activite::create([
              'libelle' => $request->libelle,
              'duree' => $dureeInTimeFormat,
              'nbrPlaces' => $request->nbrPlaces,
              'idEntreprise' => $entreprise->id
          ]);

          $activite->travailler_users()->attach(auth()->id(), [
              'idEntreprise' => $entreprise->id,
              'statut' => 'Admin',
          ]);

          // Ajouter les employés de l'entreprise à l'activité
            $entreprise->travailler_users()->where('statut', 'Employé')->get()->each(function ($user) use ($activite, $entreprise) {
                $activite->travailler_users()->attach($user->id, [
                    'idEntreprise' => $entreprise->id,
                    'statut' => 'Employé',
                ]);
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
        // Check if the user is an Admin.
        $isAdmin = Auth::user()->travailler_entreprises()
                ->wherePivot('statut', 'Admin')
                ->wherePivot('idEntreprise', $entreprise->id)
                ->count() > 0;

        // Check if the user is the creator.
        $isCreator = $entreprise->idCreateur == Auth::user()->id;

        $isAllow = $isAdmin || $isCreator;
        if (!$isAllow) {
            return redirect()->route('entreprise.index');
        } else {
            // Retrieve the activity based on its ID and the company.
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
                'duree' => 'required|integer|min:1', // Durée en minutes
                'nbrPlaces' => [
                    'required',
                    'integer',
                    'min:1',
                    'max:' . $entreprise->capaciteMax, // Limite le nombre de places à la capacité de l'entreprise
                ]
            ]);

            $service = Activite::findOrFail($id);

            // Convertir la durée (minutes) en format H:i:s
            $dureeInTimeFormat = gmdate('H:i:s', $request->duree * 60);

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
        // Check if the user is an Admin.
        $isAdmin = Auth::user()->travailler_entreprises()
                ->wherePivot('statut', 'Admin')
                ->wherePivot('idEntreprise', $entreprise->id)
                ->count() > 0;

        // Check if the user is the creator.
        $isCreator = $entreprise->idCreateur == Auth::user()->id;

        $isAllow = $isAdmin || $isCreator;
        if (!$isAllow) {
            return redirect()->route('entreprise.index');
        } else {
            // Retrieve the activity.
            $service = Activite::findOrFail($id);
            // Detach all associated users, time slots (plages), and company relations.
            $service->travailler_users()->detach();
            $service->plages()->detach();
            $service->travailler_entreprises()->detach();
            // Delete the activity.
            $service->delete();

            // If no activities remain for the company, update its published status.
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
    public function createPlage(Request $request, Entreprise $entreprise, $id)
    {
        // If the request is an AJAX call.
        if ($request->ajax()) {
            // For non-guest users.
            if (Auth::user()->travailler_entreprises
                    ->where('id', $entreprise->id)
                    ->first()->pivot->statut != 'Invité') {
                // Retrieve the specified activity.
                $activite = Activite::where('id', $id)
                    ->where('idEntreprise', $entreprise->id)
                    ->first();

                if ($activite) {
                    // Get the list of time slot IDs associated with the activity.
                    $plageIds = $activite->plages()->pluck('idPlage');
                    // Retrieve the time slots data.
                    $data = Plage::whereIn('id', $plageIds)
                        ->get(['id', 'heureDeb', 'heureFin', 'datePlage', 'interval']);
                    return response()->json($data);
                } else {
                    // Return an error if the activity is not found.
                    return response()->json(['error' => 'Activite not found'], 404);
                }
            } else {
                // If the user is a guest, return the view for creating a time slot.
                $service = Activite::findOrFail($id);
                return view('plage.create', ['entreprise' => $entreprise, 'activite' => $service]);
            }
        }
        // For non-AJAX requests, return the view.
        $service = Activite::findOrFail($id);
        return view('plage.create', ['entreprise' => $entreprise, 'activite' => $service]);
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
    public function ajaxPlage(Request $request, Entreprise $entreprise, $id)
    {
        switch ($request->type) {
            case 'add':
                // Create a new time slot with a default interval if none is provided.
                if (!$request->interval) {
                    $event = Plage::create([
                        'heureDeb'      => $request->heureDeb,
                        'heureFin'      => $request->heureFin,
                        'datePlage'     => $request->datePlage,
                        'interval'      => '00:05:00',
                        'planTables'    => json_encode(['UnTest']),
                        'entreprise_id' => $request->entreprise_id,
                    ]);
                } else {
                    $event = Plage::create([
                        'heureDeb'      => $request->heureDeb,
                        'heureFin'      => $request->heureFin,
                        'datePlage'     => $request->datePlage,
                        'interval'      => $request->interval,
                        'planTables'    => json_encode(['UnPlanDeTables']),
                        'entreprise_id' => $request->entreprise_id,
                    ]);
                }

                // Attach the selected employees to the time slot.
                foreach ($request->employes_affecter as $idEmploye) {
                    $event->employes()->attach($idEmploye);
                }

                // Link the time slot to the specified activity.
                $event->activites()->attach($id);

                return response()->json($event);
                break;

            case 'update':
                // Update the time slot's start time, end time, and date.
                $event = Plage::where($request->id)->first()->update([
                    'heureDeb'  => $request->heureDeb,
                    'heureFin'  => $request->heureFin,
                    'datePlage' => $request->datePlage,
                ]);

                return response()->json($event);
                break;

            case 'delete':
                // Retrieve the activity and the time slot to delete.
                $activite = Activite::where("id", $id)->first();
                $plage = Plage::where("id", $request->id)->first();
                // Detach all associated employees and activities.
                $plage->employes()->detach();
                $plage->activites()->detach();
                $event = $plage->delete();

                return response()->json($event);
                break;

            case 'modify':
                // Modify the time slot by updating the employee assignments.
                $event = Plage::where("id", $request->id)->first();

                // Detach current employee assignments.
                $event->employes()->detach();

                // Attach the newly selected employees.
                foreach ($request->employes_affecter as $idEmploye) {
                    $event->employes()->attach($idEmploye);
                }

                return response()->json($event);
                break;

            default:
                // If the operation type is not recognized, do nothing.
                break;
        }
    }
}
