<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Contracts\View\Factory;
use Illuminate\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Models\Reservation;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use App\Models\Entreprise;
use App\Models\Notification;
use App\Models\Activite;

/**
 * @brief Controller for managing reservation-related operations.
 *
 * This controller handles listing, viewing, creating, updating, and deleting reservations.
 * It also manages the associated notifications and the relationships between reservations,
 * activities, and enterprises.
 */
class ReservationController extends Controller
{
    /**
     * Display a paginated list of reservations for the authenticated user.
     *
     * The method checks if the authenticated user has any reservations performed via the
     * "effectuer_reservations" relationship. If yes, it returns the reservation index view
     * with the paginated reservations; otherwise, it returns the view with an empty list.
     *
     * @return View The view displaying the list of reservations.
     */
    public function index() : View
    {
        if (Auth::user()->effectuer_reservations()->count() > 0) {
            return view('reservation.index', [
                'reservations' => Auth::user()->effectuer_reservations()->simplePaginate(9)
            ]);
        } else {
            return view('reservation.index', [
                'reservations' => []
            ]);
        }
    }

    /**
     * Display details of a specific reservation.
     *
     * @param Reservation $reservation The reservation instance to display.
     *
     * @return Factory|\Illuminate\Contracts\View\View|Application The view showing the reservation details.
     */
    public function show(Reservation $reservation): Factory|\Illuminate\Contracts\View\View|Application
    {
        return view('reservation.show', compact('reservation'));
    }

    /**
     * Show the form for creating a new reservation.
     *
     * Retrieves existing reservations associated with the given activity (via the pivot table "effectuer")
     * and returns the reservation creation view along with the specified enterprise and activity.
     *
     * @param Entreprise $entreprise The enterprise for which the reservation is being made.
     * @param Activite   $activite   The activity associated with the reservation.
     *
     * @return View The view for creating a new reservation.
     */
    public function create(Entreprise $entreprise, Activite $activite): View
    {
        // Récupérer les réservations spécifiques à l'activité
        /* $reservations = Reservation::whereIn('id', function ($query) use ($activite) {
            $query->select('idReservation')
                  ->from('effectuer')
                  ->where('idActivite', $activite->id);
        })->get();
    
        // Récupérer toutes les réservations de l’entreprise (toutes activités confondues)
        $reservationsEntreprise = Reservation::whereIn('id', function ($query) use ($entreprise) {
            $query->select('idReservation')
                  ->from('effectuer')
                  ->whereIn('idActivite', function ($subQuery) use ($entreprise) {
                      $subQuery->select('id')
                               ->from('activites')
                               ->where('idEntreprise', $entreprise->id);
                  });
        })->get();
    
        return view('reservation.create', [
            'entreprise' => $entreprise,
            'activite' => $activite,
            'reservations' => $reservations,
            'reservationsEntreprise' => $reservationsEntreprise, // Ajout pour vérification globale
        ]); */
        $reservations = $this->getReservations($activite);
        $reservationsEntreprise = $this->getReservationsEntreprise($entreprise);
        $plages = $this->getPlages($activite);

        // Calculate available time slots
        $timeSlots = $this->calculateTimeSlots(
            $plages,
            $reservations,
            $reservationsEntreprise,
            $entreprise,
            $activite,
            now()->format('Y-m-d')
        );

        return view('reservation.create', [
            'entreprise' => $entreprise,
            'activite' => $activite,
            'reservations' => $reservations,
            'reservationsEntreprise' => $reservationsEntreprise,
            'timeSlots' => $timeSlots,
        ]);
    }

    /**
     * Store a newly created reservation in storage.
     *
     * This method validates the input data, extracts the start and end times from the given "horaire" string,
     * removes any previous reservation for the same activity by the user (along with its notifications and pivot
     * relationships), creates a new reservation, handles its associated notifications, and finally attaches the
     * reservation to the activity and the authenticated user.
     *
     * @param Request     $request     The HTTP request instance containing reservation data.
     * @param Entreprise  $entreprise  The enterprise associated with the reservation.
     * @param Activite    $activite    The activity associated with the reservation.
     *
     * @return RedirectResponse A redirect response to the reservation index with a success message.
     */
    public function store(Request $request, Entreprise $entreprise, Activite $activite): RedirectResponse
    {
        // Validate the incoming request data.
        $validated = $request->validate([
            'dateRdv'        => 'required|date_format:Y-m-d',
            'horaire'        => 'required|string',
            'nbPersonnes'    => 'nullable|integer|min:1',
            'notifications'  => 'sometimes|array',
            'notifications.*.typeNotification' => 'sometimes|string|in:SMS,Mail',
            'notifications.*.contenu'          => 'sometimes|string',
            'notifications.*.duree'            => 'sometimes|string|in:1jour,2jours,1semaine',
            'employe_id'     => 'required|integer|exists:users,id',
        ]);

        // Extract start and end times from the "horaire" field (expected format: "HH:MM - HH:MM").
        [$heureDeb, $heureFin] = explode(' - ', $validated['horaire']);

        // Check if there is an existing reservation for this activity by the user.
        $previousReservation = Auth::user()
            ->effectuer_reservations()
            ->whereHas('effectuer_activites', function ($query) use ($activite) {
                $query->where('idActivite', $activite->id);
            })
            ->first();

        if ($previousReservation) {
            // Delete all notifications associated with the previous reservation.
            $previousReservation->notifications()->each(function ($notification) {
                $notification->delete();
            });

            // Detach pivot relationships for the previous reservation.
            $previousReservation->effectuer_activites()->detach();

            // Delete the previous reservation.
            $previousReservation->delete();
        }

        // (Note: The deletion block appears twice in the original code.
        // Only one block is necessary, so the duplicate code is omitted.)

        // Create the new reservation with the validated data.
        $reservation = Reservation::create([
            'dateRdv'     => $validated['dateRdv'],
            'heureDeb'    => Carbon::parse($heureDeb)->format('H:i:s'),
            'heureFin'    => Carbon::parse($heureFin)->format('H:i:s'),
            'nbPersonnes' => $validated['nbPersonnes'] ?? 1,
        ]);

        // Process and attach notifications, if provided.
        foreach ($validated['notifications'] ?? [] as $notificationData) {
            $notification = new Notification([
                'categorie'      => $notificationData['typeNotification'],
                'contenu'        => $notificationData['contenu'],
                'delai'          => match ($notificationData['duree']) {
                    '1jour'   => 24,
                    '2jours'  => 48,
                    '1semaine'=> 168,
                },
                'etat'           => 0,
                'reservation_id' => $reservation->id,
            ]);

            $reservation->notifications()->save($notification);
        }

        // Attach the reservation to the activity and associate it with the authenticated user.
        $reservation->effectuer_activites()->attach($activite->id, [
            'idUser'          => Auth::id(),
            'dateReservation' => now(),
            'typeNotif'       => 'SMS',
            'numTel'          => Auth::user()->numTel,
        ]);

        return redirect()
            ->route('reservation.index')
            ->with('success', 'Votre réservation a été enregistrée avec succès !');
    }

    /**
     * Show the form for editing an existing reservation.
     *
     * Retrieves the first activity associated with the reservation and all reservations related to
     * that activity. Also, obtains the enterprise via the activity relationship. Then, it passes these
     * variables to the reservation edit view.
     *
     * @param Reservation $reservation The reservation instance to edit.
     *
     * @return Factory|\Illuminate\Contracts\View\View|Application The view for editing the reservation.
     */
    public function edit(Reservation $reservation): Factory|\Illuminate\Contracts\View\View|Application
    {
        // Retrieve the first activity associated with the reservation.
        $activite = $reservation->effectuer_activites()->first();
        if (! $activite) {
            return redirect()->route('reservation.index')
                ->with('error', 'Aucune activité associée à cette réservation.');
        }

        // Retrieve all reservations related to this activity via the pivot table "effectuer".
        $allReservations = Reservation::whereIn('id', function ($query) use ($activite) {
            $query->select('idReservation')
                ->from('effectuer')
                ->where('idActivite', $activite->id);
        })->get();

        $reservations = $allReservations;

        // Retrieve the enterprise associated with the activity.
        $entreprise = $activite->entreprise;

        return view('reservation.edit', compact('reservation', 'activite', 'reservations', 'entreprise'));
    }

    /**
     * Update an existing reservation in storage.
     *
     * This method validates the updated data, extracts the new time slot and date from the "slot" field,
     * creates a new reservation with the updated details, attaches it to the same activity and user via the pivot
     * table, and deletes the old reservation along with its relationships.
     *
     * @param Request     $request     The HTTP request containing updated reservation data.
     * @param Reservation $reservation The existing reservation instance to update.
     *
     * @return RedirectResponse A redirect response to the reservation index with a success message.
     */
    public function update(Request $request, Reservation $reservation): RedirectResponse
    {
        // Validate the incoming request data.
        $validated = $request->validate([
            'slot'        => 'required|string', // Expected format: "HH:MM - HH:MM|YYYY-MM-DD"
            'nbPersonnes' => 'integer|min:1',
        ]);

        // Extract the time range and the date from the "slot" field.
        [$timeRange, $jour] = explode('|', $validated['slot']);
        [$hDeb, $hFin] = explode(' - ', $timeRange);

        // Create a new reservation with the updated time and date.
        $newReservation = Reservation::create([
            'dateRdv'     => $jour,
            'heureDeb'    => Carbon::parse($hDeb)->format('H:i:s'),
            'heureFin'    => Carbon::parse($hFin)->format('H:i:s'),
            'nbPersonnes' => $reservation->nbPersonnes,
        ]);

        // Retrieve the activity linked to the old reservation.
        $activite = $reservation->effectuer_activites()->first();

        // Attach the new reservation to the activity and the authenticated user.
        $newReservation->effectuer_activites()->attach($activite->id, [
            'idUser'          => Auth::id(),
            'dateReservation' => now(),
            'typeNotif'       => 'SMS',
            'numTel'          => Auth::user()->numTel,
        ]);

        // Delete the old reservation and its related data.
        $this->destroy($reservation);

        return redirect()
            ->route('reservation.index')
            ->with('success', 'Votre réservation a été modifiée avec succès !');
    }

    /**
     * Remove the specified reservation from storage.
     *
     * This method deletes all notifications linked to the reservation, detaches its pivot relationships
     * (e.g., with activities and users), and then deletes the reservation itself.
     *
     * @param Reservation $reservation The reservation instance to delete.
     *
     * @return RedirectResponse A redirect response to the reservation index with a success message.
     */
    public function destroy(Reservation $reservation): RedirectResponse
    {
        // Delete all notifications associated with the reservation.
        $reservation->notifications()->delete();

        // Detach the reservation from all related pivot relationships.
        $reservation->effectuer_activites()->detach();
        $reservation->affecter_users()->detach(); // If the 'affecter_users' relationship exists.

        // Delete the reservation.
        $reservation->delete();

        return redirect()
            ->route('reservation.index')
            ->with('success', 'Réservation et notifications supprimées avec succès !');
    }

    /**
     * @brief Retrieve the time slots (plages) for the current activity.
     * 
     * This method queries the activity's associated time slots.
     * 
     * @param Activite $activite The activity for which time slots are retrieved.
     * @return array Array of time slot objects.
     */
    private function getPlages(Activite $activite): array
    {
        return $activite->plages()->get()->toArray();
    }

    /**
     * @brief Retrieve reservations for the specified activity.
     * 
     * @param Activite $activite The activity whose reservations are retrieved.
     * @return array Array of reservation objects.
     */
    private function getReservations(Activite $activite): array
    {
        return Reservation::whereIn('id', function ($query) use ($activite) {
            $query->select('idReservation')
                ->from('effectuer')
                ->where('idActivite', $activite->id);
        })->get()->toArray();
    }

    /**
     * @brief Retrieve all reservations for the enterprise.
     * 
     * @param Entreprise $entreprise The enterprise for which reservations are retrieved.
     * @return array Array of reservation objects.
     */
    private function getReservationsEntreprise(Entreprise $entreprise): array
    {
        return Reservation::whereIn('id', function ($query) use ($entreprise) {
            $query->select('idReservation')
                ->from('effectuer')
                ->whereIn('idActivite', function ($subQuery) use ($entreprise) {
                    $subQuery->select('id')
                            ->from('activites')
                            ->where('idEntreprise', $entreprise->id);
                });
        })->get()->toArray();
    }


    /**
     * @brief Calculates available time slots for reservations.
     * 
     * Processes defined time slots, existing reservations, 
     * and returns an array of available slots with remaining capacity.
     * 
     * @param array $slots Array of available time slots.
     * @param array $reservations Array of reservations for the activity.
     * @param array $companyReservations Array of global company reservations.
     * @param object $company Object representing the company (includes maximum capacity).
     * @param object $activity Object representing the activity (includes maximum places).
     * @param string $date Date for which the time slots are calculated (format 'Y-m-d').
     * 
     * @return array Array of available time slots with remaining capacity.
     * 
     * @throws \Exception If an error occurs during time parsing with Carbon.
     */
    /* public function calculateTimeSlots(
        array $slots, 
        array $reservations, 
        array $companyReservations, 
        object $company, 
        object $activity, 
        string $date
    ): array {
        $timeSlots = [];

        // Loop through all defined time slots
        foreach ($slots as $slot) {
            try {
                $startTime = Carbon::parse($slot->heureDeb);
                $endTime = Carbon::parse($slot->heureFin);
                $interval = Carbon::parse($slot->activites->first()->duree)->minute + Carbon::parse($slot->activites->first()->duree)->hour * 60;
            } catch (\Exception $e) {
                // Skip this slot if time parsing fails
                continue;
            }

            // Iterate through intervals within the time slot
            while ($startTime->lessThan($endTime)) {
                // Set current time range
                $currentStart = $slot->datePlage->copy()->setTimeFromTimeString($startTime->format('H:i:s'));
                $currentEnd = $currentStart->copy()->addMinutes($interval);

                // Calculate reserved places for the activity
                $activityReservations = array_reduce($reservations, function ($sum, $res) use ($date, $currentStart, $currentEnd) {
                    if ($res->dateRdv->format('Y-m-d 00:00:00') !== $date) {
                        return $sum;
                    }

                    $resStart = Carbon::createFromFormat('Y-m-d H:i:s', $res->dateRdv->format('Y-m-d') . ' ' . $res->heureDeb);
                    $resEnd = Carbon::createFromFormat('Y-m-d H:i:s', $res->dateRdv->format('Y-m-d') . ' ' . $res->heureFin);

                    // Check for time overlap
                    if ($currentStart->lt($resEnd) && $currentEnd->gt($resStart)) {
                        return $sum + $res->nbPersonnes;
                    }
                    return $sum;
                }, 0);

                // Calculate reserved places for the entire company
                $companyReservationsCount = array_reduce($companyReservations, function ($sum, $res) use ($date, $currentStart, $currentEnd) {
                    if ($res->dateRdv->format('Y-m-d 00:00:00') !== $date) {
                        return $sum;
                    }

                    $resStart = Carbon::createFromFormat('Y-m-d H:i:s', $res->dateRdv->format('Y-m-d') . ' ' . $res->heureDeb);
                    $resEnd = Carbon::createFromFormat('Y-m-d H:i:s', $res->dateRdv->format('Y-m-d') . ' ' . $res->heureFin);

                    // Check for time overlap
                    if ($currentStart->lt($resEnd) && $currentEnd->gt($resStart)) {
                        return $sum + $res->nbPersonnes;
                    }
                    return $sum;
                }, 0);

                // Calculate remaining places
                $globalRemaining = $company->capaciteMax - $companyReservationsCount;
                $activityRemaining = $activity->nbrPlaces - $activityReservations;
                $remainingPlaces = min($globalRemaining, $activityRemaining);

                // Add current time slot to the result array
                $timeSlots[] = [
                    'time_range' => $currentStart->format('H:i') . ' - ' . $currentEnd->format('H:i'),
                    'date' => $currentStart->format('Y-m-d'),
                    'remaining_places' => $remainingPlaces,
                    'start' => $currentStart->format('H:i'),
                    'end' => $currentEnd->format('H:i'),
                ];

                // Move to the next interval
                $startTime->addMinutes($interval);
            }
        }

        return $timeSlots;
    } */
    public function calculateTimeSlots(
        array $plages, 
        array $reservations, 
        array $reservationsEntreprise, 
        object $entreprise, 
        object $activite, 
        string $date
    ): array {
        $timeSlots = [];
    
        foreach ($plages as $plage) {
            try {
                $heureDeb = \Carbon\Carbon::parse($plage['heureDeb']);
                $heureFin = \Carbon\Carbon::parse($plage['heureFin']);
                $interval = \Carbon\Carbon::parse($plage['interval'])->hour * 60 
                          + \Carbon\Carbon::parse($plage['interval'])->minute;
                
                // ✅ Vérification correcte du format
                //dump("Plage Start: {$heureDeb}, End: {$heureFin}, Interval: {$interval} min");
            } catch (\Exception $e) {
                //dump("Error parsing plage: " . $e->getMessage());
                continue;
            }
    
            // 🟡 Correction ici : Ajouter une condition pour éviter boucle infinie
            $loopCount = 0;
            while ($heureDeb->lessThan($heureFin) && $loopCount < 100) {
                $currentStart = \Carbon\Carbon::parse($plage['datePlage'])
                                              ->setTimeFromTimeString($heureDeb->format('H:i:s'));
                $currentEnd = $currentStart->copy()->addMinutes($interval);
    
                //dump("Interval: {$currentStart->format('H:i')} - {$currentEnd->format('H:i')}");
    
                // Ajoute un créneau au tableau
                $timeSlots[] = [
                    'date' => $currentStart->format('Y-m-d'),
                    'time_range' => $currentStart->format('H:i') . ' - ' . $currentEnd->format('H:i'),
                    'remaining_places' => 10, // Test fixe
                ];
    
                $heureDeb->addMinutes($interval);
                $loopCount++;
            }
        }
    
        // ✅ Vérification finale
        //dd('Calculated timeSlots:', $timeSlots);
    
        return $timeSlots;
    }
    
}

