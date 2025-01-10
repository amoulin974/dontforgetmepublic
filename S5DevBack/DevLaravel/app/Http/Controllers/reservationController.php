<?php

namespace App\Http\Controllers;

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
            return view('reservation.index', [
                'reservations' => Reservation::where('id',Auth::user()->effectuer_reservations()->pluck('idReservation')) // Récupérer les réservations effectuées par l'utilisateur
                ->simplePaginate(9)
            ]);
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
    public function show(Reservation $reservation) : View
    {
        return view('reservation.show', [
            'reservation' => $reservation
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return View
     */
    public function create(Entreprise $entreprise): View
    {
        $reservation = new Reservation();
        $notification = new Notification();

        return view('reservation.create', [
            'entreprise' => $entreprise,
            'reservation' => $reservation
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  App\Http\Requests\FormPostRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
{
    // Validation des données du formulaire
    $validated = $request->validate([
        'dateRdv' => 'required|date_format:Y-m-d H:i:s', // Exemple : "2025-01-09 00:00:00"
        'horaire' => 'required|string', // Exemple : "09:00 - 10:00"
        'nbPersonnes' => 'required|integer|min:1', // Nombre de personnes
        'notifications' => 'required|array', // Notifications doivent être un tableau
        'notifications.*.typeNotification' => 'required|string|in:SMS,Mail', // Type : SMS ou Mail
        'notifications.*.contenu' => 'required|string', // Contenu : email ou numéro
        'notifications.*.duree' => 'required|string|in:1jour,2jours,1semaine', // Durée : "1jour", "2jours", "1semaine"
    ]);

    // Extraction des heures à partir de 'horaire'
    [$heureDeb, $heureFin] = explode(' - ', $validated['horaire']);

    // Création de la réservation
    $reservation = Reservation::create([
        'dateRdv' => $validated['dateRdv'], // Date de la plage choisie
        'heureDeb' => $heureDeb, // Heure de début
        'heureFin' => $heureFin, // Heure de fin
        'nbPersonnes' => $validated['nbPersonnes'], // Nombre de personnes
    ]);

    // Parcourir les notifications et les associer à la réservation
    foreach ($validated['notifications'] as $notificationData) {
        $notification = new Notification([
            'categorie' => $notificationData['typeNotification'], // Type : SMS ou Mail
            'contenu' => $notificationData['contenu'], // Email ou numéro de téléphone
            'delai' => match ($notificationData['duree']) { // Calcul du délai de rappel
                '1jour' => now()->addDay(),
                '2jours' => now()->addDays(2),
                '1semaine' => now()->addWeek(),
            },
            'etat' => 1, // Actif par défaut
        ]);

        // Associer la notification à la réservation via la relation notifications()
        $reservation->notifications()->save($notification);
    }

    // Rediriger avec un message de succès
    return redirect()
        ->route('reservation.show', ['reservation' => $reservation->id])
        ->with('success', 'La réservation et les notifications ont été ajoutées avec succès.');
}



    /**
     * Show the form for editing the specified resource.
     *
     * @param  Reservation $reservation
     * @return \Illuminate\Http\Response
     */
    public function edit(Reservation $reservation)
    {
        // À modifier
        if((Auth::user()->id) || (Auth::user()->superadmin)) {
            return view('reservation.edit' , ['reservation' => $reservation]);        
        }
        else {
            return redirect()->route('reservation.index');
        }  
    }

    /**
     * Update the specified resource in storage.
     *
     * @param FormPostRequest  $request
     * @param  Reservation  $reservation
     * @return \Illuminate\Http\Response
     */
    public function update(Reservation $reservation, FormPostRequest $request)
    {
        $reservation->update($request->validated());

        return redirect()->route('reservation.show', ['reservation' => $reservation->id])->with('success', 'La réservation a été modifiée avec succès.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Reservation  $reservation
     * @return \Illuminate\Http\Response
     */
    public function destroy(Reservation $reservation)
    {
        $reservation = Reservation::findOrFail($reservation->id);

        if((Auth::user()->id) || (Auth::user()->superadmin)) {
            $reservation->delete();

            return redirect()->route('reservation.index')->with('success', 'Réservation supprimée avec succès');
        }
        else {
            return redirect()->route('reservation.index');
        }  
    }
}
