<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\Reservation;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\View\View;
use App\Http\Requests\FormPostRequest;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use App\Models\Entreprise;
use App\Models\Notification;
use App\Models\Activite;

class reservationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Illuminate\View\View
     */
    public function index() : View
    {
        if (Auth::user()->effectuer_reservations()->count() > 0) {
            return view('reservation.index', ['reservations' => Auth::user()->effectuer_reservations()->simplePaginate(9)]);
        }
        else{
            return view('reservation.index', [
                'reservations' => []
            ]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Illuminate\View\View
     */
    public function show(Reservation $reservation)
    {
        return view('reservation.show', compact('reservation'));
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return View
     */
    public function create(Entreprise $entreprise, Activite $activite): View
    {
        /*return view('reservation.create', [
            'entreprise' => $entreprise,
            'activite' => $activite,
        ]);*/

        //$date = now()->toDateString();

        //$reservations = Reservation::where('dateRdv', $date)
        //    ->where('activite_id', $activite->id)
        //    ->get();
        $reservations = Reservation::whereIn('id', function ($query) use ($activite) {
            $query->select('idReservation')
                  ->from('effectuer')
                  ->where('idActivite', $activite->id);
        })->get();

        return view('reservation.create', [
            'entreprise' => $entreprise,
            'activite' => $activite,
            'reservations' => $reservations,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  App\Http\Requests\FormPostRequest  $request
     * @return Response
     */
    public function store(Request $request, Entreprise $entreprise, Activite $activite)
    {
        // Validation des données du formulaire
        $validated = $request->validate([
            'dateRdv' => 'required|date_format:Y-m-d', // Exemple : "2025-01-09"
            'horaire' => 'required|string', // Exemple : "09:00 - 10:00"
            'nbPersonnes' => 'nullable|integer|min:1', // Nombre de personnes
            'notifications' => 'sometimes|array', // Notifications doivent être un tableau
            'notifications.*.typeNotification' => 'sometimes|string|in:SMS,Mail', // Type : SMS ou Mail
            'notifications.*.contenu' => 'sometimes|string', // Contenu : email ou numéro
            'notifications.*.duree' => 'sometimes|string|in:1jour,2jours,1semaine', // Durée : "1jour", "2jours", "1semaine"
            'employe_id' => 'required|integer|exists:users,id', // Employé doit exister dans la table users
        ]);

        // Extraction des heures à partir de 'horaire'
        [$heureDeb, $heureFin] = explode(' - ', $validated['horaire']);

        // Création de la réservation
        $reservation = Reservation::create([
            'dateRdv' => $validated['dateRdv'], // Date de la plage choisie
            'heureDeb' => \Carbon\Carbon::parse($heureDeb)->format('H:i:s'), // Heure de début
            'heureFin' => \Carbon\Carbon::parse($heureFin)->format('H:i:s'), // Heure de fin
            'nbPersonnes' => $validated['nbPersonnes'] ?? 1, // Nombre de personnes
        ]);

        // Parcourir les notifications et les associer à la réservation
        foreach ($validated['notifications'] ?? [] as $notificationData) {
            $notification = new Notification([
                'categorie' => $notificationData['typeNotification'], // Type : SMS ou Mail
                'contenu' => $notificationData['contenu'], // Email ou numéro de téléphone
                'delai' => match ($notificationData['duree']) { // Calcul du délai de rappel
                    '1jour' => 24,
                    '2jours' => 48,
                    '1semaine' => 168,
                },
                'etat' => 0, // Non envoyé par défaut
                'reservation_id' => $reservation->id
            ]);

            // Associer la notification à la réservation via la relation notifications()
            $reservation->notifications()->save($notification);
        }

        // Associer l'employé à la réservation via la relation affecter_users
        $reservation->affecter_users()->attach($validated['employe_id']);

        Auth::user()->effectuer_activites()->attach($activite->id, ['idReservation' => $reservation->id,'dateReservation' => now(), 'typeNotif' => 'SMS', 'numTel' => Auth::user()->numtel]);  

        // Rediriger avec un message de succès
        return redirect()
            ->route('reservation.index')
            ->with('success', 'La réservation et les notifications ont été ajoutées avec succès.');
    }



    /**
     * Show the form for editing the specified resource.
     *
     * @param  Reservation $reservation
     * @return RedirectResponse
     */
    public function edit(Reservation $reservation)
    {
        // Récupérer la première activité liée à la réservation
        $activite = $reservation->effectuer_activites()->first();
        if (! $activite) {
            return redirect()->route('reservation.index')->with('error', 'Aucune activité associée à cette réservation.');
        }

        // Récupérer toutes les réservations liées à cette activité
        $allReservations = Reservation::whereIn('id', function ($query) use ($activite) {
            $query->select('idReservation')
                ->from('effectuer')
                ->where('idActivite', $activite->id);
        })->get();

        $reservations = $allReservations;

        // Récupérer l'entreprise associée via l'activité
        $entreprise = $activite->entreprise;

        // On passe tout ça à la vue
        return view('reservation.edit', compact('reservation', 'activite', 'reservations', 'entreprise'));
    }



    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  Reservation  $reservation
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Reservation $reservation)
    {
        // Valider les données
        $validated = $request->validate([
            'slot'         => 'required|string', // Ex: "09:00 - 10:00|2025-02-01"
            'nbPersonnes'  => 'integer|min:1',
        ]);

        // 1) Extraire l'horaire et la date depuis 'slot'
        [$timeRange, $jour] = explode('|', $validated['slot']);
        [$hDeb, $hFin] = explode(' - ', $timeRange);

        // 2) Créer la nouvelle réservation
        $newReservation = Reservation::create([
            'dateRdv'     => $jour,
            'heureDeb'    => \Carbon\Carbon::parse($hDeb)->format('H:i:s'),
            'heureFin'    => \Carbon\Carbon::parse($hFin)->format('H:i:s'),
            'nbPersonnes' => $reservation->nbPersonnes,
        ]);

        // 3) Récupérer l’activité liée à l’ancienne réservation
        $activite = $reservation->effectuer_activites()->first();

        // 4) Attacher la nouvelle réservation (table pivot 'effectuer')
        $newReservation->effectuer_activites()->attach($activite->id, [
            'idUser'          => Auth::id(),
            'dateReservation' => now(),
            'typeNotif'       => 'SMS',
            'numTel'          => Auth::user()->numTel,
        ]);

        // 5) Supprimer l’ancienne réservation et ses relations
        $reservation->notifications()->delete();
        $reservation->effectuer_activites()->detach();
        $reservation->delete();

        return redirect()
            ->route('reservation.index')
            ->with('success', 'Votre réservation a été modifiée avec succès !');
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param Reservation $reservation
     * @return RedirectResponse
     */
    public function destroy(Reservation $reservation)
    {
        $reservation->notifications()->delete();
        $reservation->effectuer_activites()->detach();
        $reservation->delete();

        return redirect()
            ->route('reservation.index')
            ->with('success', 'Réservation et notifications supprimées avec succès !');
    }
}
