<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * @brief Controller for handling notification operations.
 *
 * This controller provides methods to retrieve detailed notification data and update
 * the state of notifications.
 */
class notificationController extends Controller
{
    /**
     * Retrieve detailed notification information.
     *
     * This method performs several joins between the tables `effectuer`, `users`,
     * `reservations`, `activites`, and `entreprises` to gather comprehensive details.
     * Additionally, it executes subqueries to obtain the first notification's ID, category,
     * state, and delay (before notification) associated with each reservation.
     *
     * @return \Illuminate\Http\JsonResponse JSON response containing the detailed notification data.
     */
    public function getDetails()
    {
        $data = //DB::connection('userLecture')
            DB::table('effectuer')
                ->join('users', 'effectuer.idUser', '=', 'users.id')
                ->join('reservations', 'effectuer.idReservation', '=', 'reservations.id')
                ->join('activites', 'effectuer.idActivite', '=', 'activites.id')
                ->join('entreprises', 'activites.idEntreprise', '=', 'entreprises.id')
                ->select(
                    'users.nom AS userNom',
                    'users.prenom AS userPrenom',
                    'users.numTel AS userNumTel',
                    'users.email AS userEmail',
                    'entreprises.libelle AS entrepriseNom',
                    'reservations.dateRdv AS dateRendezVous',
                    'reservations.heureDeb AS heureRendezVous',
                    DB::raw('(SELECT n.id
                  FROM notifications n
                  WHERE n.reservation_id = reservations.id
                  ORDER BY n.id ASC
                  LIMIT 1) AS notifId'),
                    DB::raw('(SELECT n.categorie
                  FROM notifications n
                  WHERE n.reservation_id = reservations.id
                  ORDER BY n.id ASC
                  LIMIT 1) AS notifCategorie'),
                    DB::raw('(SELECT n.etat
                  FROM notifications n
                  WHERE n.reservation_id = reservations.id
                  ORDER BY n.id ASC
                  LIMIT 1) AS notifEtat'),
                    DB::raw('(SELECT n.delai
                  FROM notifications n
                  WHERE n.reservation_id = reservations.id
                  ORDER BY n.id ASC
                  LIMIT 1) AS notifDelaiAvantNotif')
                )
                ->get();

        return response()->json($data);
    }

    /**
     * Update the state of a notification.
     *
     * This method validates the input data and updates the notification state (i.e., whether it has been
     * processed or not) for the notification identified by the provided ID.
     *
     * @param Request $request The HTTP request instance containing the new state.
     * @param int|string $notificationId The ID of the notification to update.
     *
     * @return \Illuminate\Http\JsonResponse JSON response with a success or error message.
     */
    public function updateNotificationState(Request $request, $notificationId)
    {
        // Validate that the 'etat' field is provided and is a boolean.
        $request->validate([
            'etat' => 'required|boolean',
        ]);

        // Update the notification state in the database.
        $updated = DB::table('notifications')
            ->where('id', $notificationId)
            ->update([
                'etat' => $request->etat,
            ]);

        // Check if the update was successful.
        if ($updated) {
            return response()->json(['message' => 'Notification updated successfully.'], 200);
        } else {
            return response()->json(['message' => 'Notification not found or could not be updated.'], 404);
        }
    }
}
