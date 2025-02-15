<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Plage;
use App\Models\Activite;
use App\Models\Entreprise;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;

/**
 * @brief Controller for handling configuration and scheduling functionalities.
 *
 * This controller manages various functionalities such as displaying the main configuration view,
 * planning time slots (plages) for an enterprise, handling invitation responses, and performing CRUD
 * operations on time slots.
 */
class parametrageController extends Controller
{
    /**
     * Display the main configuration view.
     *
     * This method checks if the user is authenticated and is associated with any enterprise.
     * If the user is not authenticated, it redirects to the login page. If the user does not work for
     * any enterprise, it redirects to the home page. Otherwise, it returns the configuration view.
     *
     * @return \Illuminate\Http\Response The response containing the configuration view or a redirect.
     */
    public function index()
    {
        // Check if the user is authenticated.
        if (!Auth::check()) {
            return redirect()->route('login');
        }
        // If the authenticated user is not associated with any enterprise.
        else if (Auth::user()->travailler_entreprises->isEmpty()) {
            return redirect()->route('home');
        }
        // User is authenticated and associated with an enterprise.
        else {
            return view('parametrage.index', [
                'user' => Auth::user(),
            ]);
        }
    }

    /**
     * Display the scheduling view for time slots (plages) for a given enterprise.
     *
     * For AJAX requests, this method returns JSON data containing time slot details if the user is
     * either an employee or an admin of the specified enterprise. For non-AJAX requests, it checks
     * user authentication and association with the enterprise and then returns the appropriate view:
     * a view for employees or a view for admins.
     *
     * @param Request $request The HTTP request instance.
     * @param Entreprise $entreprise The enterprise for which to display time slots.
     *
     * @return \Illuminate\Http\Response The response containing JSON data or the scheduling view.
     */
    public function indexPlage(Request $request, Entreprise $entreprise)
    {
        // For AJAX requests, return time slot data as JSON.
        if ($request->ajax()) {
            // If the user's role is 'Employé' for the enterprise.
            if (Auth::user()->travailler_entreprises->where('id', $entreprise->id)->first()->pivot->statut == 'Employé') {
                $data = Plage::where('entreprise_id', $entreprise->id)
                    ->get(['id', 'heureDeb', 'heureFin', 'datePlage', 'interval']);
                return response()->json($data);
            }
            // If the user's role is 'Admin' for the enterprise.
            elseif (Auth::user()->travailler_entreprises->where('id', $entreprise->id)->first()->pivot->statut == 'Admin') {
                $data = Plage::where('entreprise_id', $entreprise->id)
                    ->get(['id', 'heureDeb', 'heureFin', 'datePlage', 'interval']);
                return response()->json($data);
            }
        }
        // For non-AJAX requests, verify user authentication and enterprise association.
        if (!Auth::check()) {
            return redirect()->route('login');
        } else if (Auth::user()->travailler_entreprises->where('id', $entreprise->id)->isEmpty()) {
            return redirect()->route('parametrage.index');
        } else {
            // Return the view based on the user's role.
            if (Auth::user()->travailler_entreprises->where('id', $entreprise->id)->first()->pivot->statut == 'Employé') {
                // Return the employee view.
                return view('plage.show', [
                    'user'       => Auth::user(),
                    'entreprise' => $entreprise,
                ]);
            } elseif (Auth::user()->travailler_entreprises->where('id', $entreprise->id)->first()->pivot->statut == 'Admin') {
                // Return the admin view.
                return view('plage.index', [
                    'user'       => Auth::user(),
                    'entreprise' => $entreprise,
                ]);
            } else {
                // Fallback redirection (normally not reached).
                return redirect()->route('parametrage.index');
            }
        }
    }

    /**
     * Display the scheduling view for time slots (plages) as an employee for a given enterprise and activity.
     *
     * For AJAX requests, this method returns JSON data containing time slot details specific to the selected
     * activity if the user's role is not "Invité". For non-AJAX requests, it verifies the user's authentication
     * and enterprise association before returning the scheduling view.
     *
     * @param Request $request The HTTP request instance.
     * @param Entreprise $entreprise The enterprise instance.
     * @param Activite $activite The activity instance for which to display time slots.
     *
     * @return \Illuminate\Http\Response The response containing JSON data or the scheduling view.
     */
    public function indexPlageAsEmploye(Request $request, Entreprise $entreprise, Activite $activite)
    {
        // For AJAX requests, return time slots specific to the activity.
        if ($request->ajax()) {
            if (Auth::user()->travailler_entreprises->where('id', $entreprise->id)->first()->pivot->statut != 'Invité') {
                // Retrieve the activity record for the given enterprise.
                $activites = Activite::where('id', $activite->id)
                    ->where('idEntreprise', $entreprise->id)
                    ->first();

                if ($activites) {
                    $plageIds = $activites->plages()->pluck('idPlage');
                    $data = Plage::whereIn('id', $plageIds)
                        ->get(['id', 'heureDeb', 'heureFin', 'datePlage', 'interval']);
                    return response()->json($data);
                } else {
                    // Return an error if the activity is not found.
                    return response()->json(['error' => 'Activite not found'], 404);
                }
            }
        }
        // For non-AJAX requests, verify user authentication and enterprise association.
        if (!Auth::check()) {
            return redirect()->route('login');
        } else if (Auth::user()->travailler_entreprises->where('id', $entreprise->id)->isEmpty()) {
            return redirect()->route('parametrage.index');
        } else {
            if (
                Auth::user()->travailler_entreprises->where('id', $entreprise->id)->first()->pivot->statut == 'Employé' ||
                Auth::user()->travailler_entreprises->where('id', $entreprise->id)->first()->pivot->statut == 'Admin'
            ) {
                // Return the scheduling view with the specified activity.
                return view('plage.show', [
                    'user'       => Auth::user(),
                    'entreprise' => $entreprise,
                    'activite'   => $activite,
                ]);
            } else {
                // Fallback redirection (normally not reached).
                return redirect()->route('parametrage.index');
            }
        }
    }

    /**
     * Process the response to an invitation.
     *
     * This method handles invitation responses by updating or deleting the pivot record
     * in the "travailler_entreprises" relationship for the authenticated user.
     * Depending on the request type ('accept' or 'reject'), it updates the user's status or removes the association.
     *
     * @param Request $request The HTTP request instance containing the invitation response data.
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse JSON response for AJAX requests or a redirect.
     */
    public function invit(Request $request)
    {
        switch ($request->type) {
            case 'accept':
                // TODO: Send email or SMS notification.
                // Update the pivot record to change the user's status.
                // (Alternate branch for "Admin" request is commented out below.)
                /* if ($request->statut == 'Admin') {
                    Auth::user()->travailler_entreprises
                        ->where('id', $request->entreprise_id)
                        ->first()->pivot->update([
                            'statut' => 'Admin',
                        ]);
                }
                else { */
                $event = Auth::user()->travailler_entreprises
                    ->where('id', $request->idEntreprise)
                    ->first()->pivot->update([
                        'statut' => 'Employé',
                    ]);
                /* } */
                return response()->json($event);
                break;

            case 'reject':
                // TODO: Send email or SMS notification.
                // Remove the association from the pivot table.
                $event = Auth::user()->travailler_entreprises
                    ->where('id', $request->idEntreprise)
                    ->first()->pivot->delete();
                return response()->json($event);
                break;

            default:
                return redirect()->route('entreprise.indexUser');
                break;
        }
    }

    /**
     * Handle AJAX requests for adding, updating, deleting, or modifying a time slot (plage).
     *
     * This method processes different operations based on the "type" parameter in the request:
     * - "add": Creates a new time slot with a default interval if none is provided.
     * - "update": Updates the start time, end time, and date of an existing time slot.
     * - "delete": Deletes an existing time slot after detaching its related activities.
     * - "modify": Updates the interval of an existing time slot.
     *
     * @param Request $request The HTTP request instance containing the operation type and data.
     *
     * @return \Illuminate\Http\JsonResponse JSON response with the result of the operation.
     */
    public function ajax(Request $request)
    {
        switch ($request->type) {
            case 'add':
                // Create a new time slot (plage) with a default interval if not provided.
                if (!$request->interval) {
                    $event = Plage::create([
                        'heureDeb'     => $request->heureDeb,
                        'heureFin'     => $request->heureFin,
                        'datePlage'    => $request->datePlage,
                        'interval'     => '00:05:00',
                        'planTables'   => json_encode(['UnTest']),
                        'entreprise_id'=> $request->entreprise_id,
                    ]);
                } else {
                    $event = Plage::create([
                        'heureDeb'     => $request->heureDeb,
                        'heureFin'     => $request->heureFin,
                        'datePlage'    => $request->datePlage,
                        'interval'     => $request->interval,
                        'planTables'   => json_encode(['UnPlanDeTables']),
                        'entreprise_id'=> $request->entreprise_id,
                    ]);
                }
                // Optionally attach related activities:
                // $event->activites()->attach($idActivites);
                return response()->json($event);
                break;

            case 'update':
                // Update the specified time slot.
                $event = Plage::find($request->id)->update([
                    'heureDeb'  => $request->heureDeb,
                    'heureFin'  => $request->heureFin,
                    'datePlage' => $request->datePlage,
                ]);
                return response()->json($event);
                break;

            case 'delete':
                // Find the time slot. Note: findOrFail returns a model, so "first()" is not needed.
                $event = Plage::findOrFail($request->id);
                // Detach all related activities.
                $event->activites()->detach();
                // Delete the time slot.
                $event = $event->delete();
                return response()->json($event);
                break;

            case 'modify':
                // Update the interval of the specified time slot.
                $event = Plage::find($request->id)->update([
                    'interval' => $request->interval,
                ]);
                return response()->json($event);
                break;

            default:
                // No action for unrecognized type.
                break;
        }
    }
}
