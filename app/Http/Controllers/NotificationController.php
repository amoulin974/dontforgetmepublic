<?php
/**
 * @file NotificationController.php
 * @brief Controller for handling notification operations.
 */
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * @class NotificationController
 * @brief Controller for handling notification operations.
 *
 * This controller provides methods to retrieve detailed notification data and update
 * the state of notifications.
 */
class NotificationController extends Controller
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
        $data = 
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
        $request->validate([
            'etat' => 'required|boolean',
        ]);

        $updated = DB::table('notifications')
            ->where('id', $notificationId)
            ->update([
                'etat' => $request->etat,
            ]);

        if ($updated) {
            return response()->json(['message' => 'Notification updated successfully.'], 200);
        } else {
            return response()->json(['message' => 'Notification not found or could not be updated.'], 404);
        }
    }

    /**
     * Delete a notification and associated reservation details by notification ID.
     *
     * This method removes a notification, the associated reservation, and related entries in `effectuer`.
     *
     * @param int|string $notificationId The ID of the notification to delete.
     *
     * @return \Illuminate\Http\JsonResponse JSON response with a success or error message.
     */
    public function destroy($notificationId)
    {
        $notification = DB::table('notifications')->where('id', $notificationId)->first();
        if (!$notification) {
            return response()->json(['message' => 'Notification not found.'], 404);
        }
        $reservationId = $notification->reservation_id;
        // Delete the notification
        DB::table('notifications')->where('id', $notificationId)->delete();
        // Delete related entries in `effectuer`
        DB::table('effectuer')->where('idReservation', $reservationId)->delete();
        // Delete the reservation
        DB::table('reservations')->where('id', $reservationId)->delete();

        return response()->json(['message' => 'Notification and associated reservation details deleted successfully.'], 200);
    }
}
