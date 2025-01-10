<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class notificationController extends Controller
{
    public function getDetails()
    {
        $data = DB::table('effectuer')
            ->join('users', 'effectuer.idUser', '=', 'users.id')
            ->join('reservations', 'effectuer.idReservation', '=', 'reservations.id')
            ->join('activites', 'effectuer.idActivite', '=', 'activites.id')
            ->join('entreprises', 'activites.idEntreprise', '=', 'entreprises.id')
            ->select(
                'users.nom AS userNom',
                'users.prenom AS userPrenom',
                'users.numTel AS userNumTel',
                'users.email AS userEmail',
                'effectuer.typeNotif AS typeNotification',
                'entreprises.libelle AS entrepriseNom',
                'reservations.heureDeb AS heureRendezVous',

                DB::raw('(SELECT n.id
                  FROM notifications n
                  WHERE n.reservation_id = reservations.id
                  ORDER BY n.id ASC
                  LIMIT 1
        ) AS notifId'),

                DB::raw('(SELECT n.etat
                  FROM notifications n
                  WHERE n.reservation_id = reservations.id
                  ORDER BY n.id ASC
                  LIMIT 1
        ) AS notifEtat'),

                DB::raw('(SELECT n.delai
                  FROM notifications n
                  WHERE n.reservation_id = reservations.id
                  ORDER BY n.id ASC
                  LIMIT 1
        ) AS notifDelaiAvantNotif')
            )
            ->get();




        return response()->json($data);
    }

    /**
     * Met à jour l'état de la notification.
     */
    public function updateNotificationState(Request $request, $notificationId)
    {
        // Valider les données d'entrée (etat doit être un boolean : 1 ou 0)
        $request->validate([
            'etat' => 'required|boolean', // Validation que 'etat' est bien un boolean (0 ou 1)
        ]);

        // Mettre à jour l'état de la notification
        $updated = DB::table('notifications')
            ->where('id', $notificationId)
            ->update([
                'etat' => $request->etat, // Nouveau statut de la notification (1 ou 0)
            ]);

        // Vérifier si la mise à jour a réussi
        if ($updated) {
            return response()->json(['message' => 'Notification mise à jour avec succès.'], 200);
        } else {
            return response()->json(['message' => 'La notification n\'a pas été trouvée ou n\'a pas pu être mise à jour.'], 404);
        }
    }
}
