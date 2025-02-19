<?php
/**
 * @file CalendrierController.php
 * @brief Controller for handling calendar-related operations.
 */
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\Reservation;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;

/**
 * @class CalendrierController
 * @brief Controller for handling calendar-related operations.
 *
 * This controller manages displaying the calendar with reservations as well as
 * processing AJAX requests for adding, updating, deleting, and modifying reservations.
 */
class CalendrierController extends Controller
{
    /**
     * Display the calendar with reservations.
     *
     * For AJAX requests, this method returns a JSON response containing the list
     * of reservations with their id, start time, end time, date, and number of persons.
     * For standard HTTP requests, it returns the calendar view along with the authenticated user.
     *
     * @param Request $request The HTTP request instance.
     *
     * @return \Illuminate\Http\Response|\Illuminate\View\View JSON response for AJAX requests or a view for standard requests.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Reservation::get(['id', 'heureDeb', 'heureFin', 'dateRdv', 'nbPersonnes']);
            return response()->json($data);
        }

        return view('calendrier.index', [
            'user' => Auth::user()
        ]);
    }

    /**
     * Handle AJAX requests for reservation operations.
     *
     * This method processes different types of operations based on the request's "type" parameter:
     * - **add**: Creates a new reservation. If the number of persons is not provided, it defaults to 1.
     * - **update**: Updates an existing reservation's start time, end time, and date.
     * - **delete**: Deletes an existing reservation.
     * - **modify**: Modifies the number of persons for an existing reservation.
     *
     * @param Request $request The HTTP request instance containing the operation type and data.
     *
     * @return \Illuminate\Http\JsonResponse JSON response containing the result of the operation.
     */
    public function ajax(Request $request)
    {
        switch ($request->type) {
            case 'add':
                if (!$request->nbPersonnes) {
                    $event = Reservation::create([
                        'heureDeb'    => $request->heureDeb,
                        'heureFin'    => $request->heureFin,
                        'dateRdv'     => $request->dateRdv,
                        'nbPersonnes' => 1,
                    ]);
                } else {
                    $event = Reservation::create([
                        'heureDeb'    => $request->heureDeb,
                        'heureFin'    => $request->heureFin,
                        'dateRdv'     => $request->dateRdv,
                        'nbPersonnes' => $request->nbPersonnes,
                    ]);
                }

                return response()->json($event);
                break;

            case 'update':
                $event = Reservation::find($request->id)->update([
                    'heureDeb' => $request->heureDeb,
                    'heureFin' => $request->heureFin,
                    'dateRdv'  => $request->dateRdv,
                ]);

                return response()->json($event);
                break;

            case 'delete':
                $event = Reservation::find($request->id)->delete();

                return response()->json($event);
                break;

            case 'modify':
                $event = Reservation::find($request->id)->update([
                    'nbPersonnes' => $request->nbPersonnes,
                ]);

                return response()->json($event);
                break;

            default:
                break;
        }
    }
}

