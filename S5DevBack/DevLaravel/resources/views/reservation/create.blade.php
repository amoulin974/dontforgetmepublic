@extends('layouts.app')

@include('base')

@section('content')

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <div class="container">
        <!-- Navigation de retour -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <a href="{{ route('entreprise.activites', ['entreprise' => $entreprise->id]) }}" class="btn btn-outline-secondary mt-4 mb-4">
                <i class="fa fa-arrow-left fa-lg" style="color: #000000;"></i>
            </a>
            <h1 class="text-center flex-grow-1">{{ $entreprise->libelle }}</h1>
        </div>

        <!-- Tableau des disponibilités -->
        <div class="availability">

            <h4 class="text-center mb-4">Disponibilités pour {{ $activite->libelle}}</h4>
            @if ($entreprise->travailler_users->where('pivot.idActivite', $activite->id)->where('pivot.statut', '!=', 'Invité')->count() > 1)
                <label for="emplye" class="form-label mt-3">Veuillez Sélectionner un employé</label>
                <select id="employe" class="form-select">
                    @foreach ($entreprise->travailler_users->where('pivot.idActivite', $activite->id)->where('pivot.statut', '!=', 'Invité') as $employe)
                        <option value="{{ $employe->id }}">{{ $employe->nom }} {{ $employe->prenom }}</option>
                    @endforeach
                </select>
            @endif

            <ul class="list-unstyled">
                @if ($activite->plages->count() > 0)
                    @foreach ($activite->plages->groupBy('datePlage') as $date => $plages)
                        <li class="mb-4">
                            <h5 class="text-primary">{{ \Carbon\Carbon::parse($date)->isoFormat('dddd D MMMM YYYY') }}</h5>
                            <div class="d-flex flex-wrap gap-2">
                                @foreach ($plages as $plage)
                                    @php
                                        try {
                                            $heureDeb = \Carbon\Carbon::parse($plage->heureDeb);
                                            $heureFin = \Carbon\Carbon::parse($plage->heureFin);
                                            $interval = \Carbon\Carbon::parse($plage->interval)->minute + \Carbon\Carbon::parse($plage->interval)->hour * 60;
                                        } catch (\Exception $e) {
                                            displayWarning('Erreur de formatage des plages horaires.');
                                            continue;
                                        }
                                    @endphp
                                    @while ($heureDeb->lessThan($heureFin))
                                        @php
                                            // On calcule les bornes de l’intervalle courant
                                            // $currentEnd   = $heureDeb->copy()->addMinutes($interval);
                                            /*$currentStart = \Carbon\Carbon::createFromFormat(
                                                'Y-m-d H:i:s',
                                                $plage->datePlage->format('Y-m-d') . ' ' . $plage->heureDeb
                                            );
                                            dd($currentStart);*/
                                            $currentStart = $plage->datePlage->copy()->setTimeFromTimeString($heureDeb->format('H:i:s'));
                                            //dd($currentStart);
                                            $currentEnd = $currentStart->copy()->addMinutes($interval);

                                            // On va déterminer si ce créneau est déjà réservé
                                            $isReserved = $reservations->contains(function($res) use ($date, $currentStart, $currentEnd) {
                                                // 1. Vérifier la date
                                                //    Attention à bien comparer des chaînes identiques
                                                //dd($res->dateRdv->format('Y-m-d 00:00:00'));
                                                //dd($date);
                                                if ($res->dateRdv->format('Y-m-d 00:00:00') !== $date) {
                                                    return false;
                                                }

                                                // 2. Vérifier le chevauchement d’horaire
                                                //    On parse l’heureDeb/heureFin de la réservation
                                                $resStart = \Carbon\Carbon::createFromFormat(
                                                    'Y-m-d H:i:s',
                                                    $res->dateRdv->format('Y-m-d').' '.$res->heureDeb
                                                );
                                                $resEnd = \Carbon\Carbon::createFromFormat(
                                                    'Y-m-d H:i:s',
                                                    $res->dateRdv->format('Y-m-d').' '.$res->heureFin
                                                );

                                                // Condition de chevauchement : (start < resEnd) ET (end > resStart)
                                                return $currentStart->lt($resEnd) && $currentEnd->gt($resStart);
                                            });
                                        @endphp

                                        @if (! $isReserved)
                                            {{-- Si pas réservé, on affiche le bouton --}}
                                            <button
                                                class="btn btn-outline-primary flex-grow-1 horaire-btn"
                                                data-bs-toggle="modal"
                                                data-bs-target="#reservationModal"
                                                data-horaire="{{ $currentStart->format('H:i') }} - {{ $currentEnd->format('H:i') }}"
                                                data-date="{{ \Carbon\Carbon::parse($date)->format('Y-m-d') }}"
                                            >
                                                {{ $currentStart->format('H:i') }} - {{ $currentEnd->format('H:i') }}
                                            </button>
                                        @endif

                                        {{-- On passe à l’intervalle suivant --}}
                                        @php
                                            $heureDeb->addMinutes($interval);
                                            //dd($heureDeb);
                                        @endphp
                                    @endwhile
                                @endforeach


                            </div>
                        </li>
                    @endforeach
                @else
                    <p>Aucune plage horaire disponible.</p>
                @endif
            </ul>
        </div>

        <!-- MODAL 1 : Réservation -->
        <div class="modal fade" id="reservationModal" tabindex="-1" aria-labelledby="reservationModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <form action="{{ route('reservation.store', ['entreprise' => $entreprise->id, 'activite' => $activite->id]) }}" method="POST" id="reservationForm">
                    @csrf
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title text-primary" id="reservationModalLabel">
                                <i class="bi bi-calendar-check"></i> Réserver une plage horaire
                            </h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <p>
                                Vous êtes sur le point de réserver pour la plage horaire suivante :
                                <strong id="selectedHoraire" class="text-success"></strong>
                                le <strong id="selectedDate" class="text-primary"></strong>.
                            </p>

                            <!-- Champs cachés pour la date et l'horaire -->
                            <input type="hidden" name="dateRdv" id="hiddenDateRdv">
                            <input type="hidden" name="horaire" id="hiddenHoraire">

                            <!-- Sélection de l'employé -->
                            @if ($entreprise->travailler_users->where('pivot.idActivite', $activite->id)->where('pivot.statut', '!=', 'Invité')->count() > 1)
                                <div class="form-group mb-3">
                                    <label for="employeSelect" class="form-label">Sélectionnez un employé :</label>
                                    <select name="employe_id" id="employeSelect" class="form-select" required>
                                        @foreach ($entreprise->travailler_users->where('pivot.idActivite', $activite->id)->where('pivot.statut', '!=', 'Invité') as $employe)
                                            <option value="{{ $employe->id }}">{{ $employe->nom }} {{ $employe->prenom }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            @elseif ($entreprise->travailler_users->where('pivot.idActivite', $activite->id)->where('pivot.statut', '!=', 'Invité')->count() === 1)
                                @php
                                    $employe = $entreprise->travailler_users->where('pivot.idActivite', $activite->id)->where('pivot.statut', '!=', 'Invité')->first();
                                @endphp
                                <input type="hidden" name="employe_id" value="{{ $employe->id }}">
                                <p>
                                    Employé affecté automatiquement : <strong>{{ $employe->nom }} {{ $employe->prenom }}</strong>
                                </p>
                            @endif

                            <!-- Nombre de personnes -->
                            @if ($entreprise->typeRdv[0] == 1)
                                <div class="form-group mb-3">
                                    <label for="nbPersonnes" class="form-label">
                                        <i class="bi bi-people-fill"></i> Nombre de personnes :
                                    </label>
                                    <input
                                        type="number"
                                        name="nbPersonnes"
                                        id="nbPersonnes"
                                        class="form-control"
                                        placeholder="Entrez le nombre de personnes"
                                        min="1"
                                        required
                                    >
                                </div>
                            @endif
                            <!-- Liste des notifications ajoutées -->
                            <h5 class="mt-4">Notifications ajoutées :</h5>
                            <ul id="notificationsList" class="list-group"></ul>

                            <!-- Bouton : Ajouter une nouvelle notification -->
                            <button type="button" class="btn btn-success w-100 mt-3" id="addNotificationBtn" data-bs-toggle="modal"
                                    data-bs-target="#notificationModal">
                                <i class="bi bi-plus-circle"></i> {{__('Add notification')}}
                            </button>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary w-100">Confirmer la réservation</button>
                            <button type="button" class="btn btn-secondary w-100" data-bs-dismiss="modal">Annuler</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- MODAL 2 : Ajouter une notification -->
        <div class="modal fade" id="notificationModal" tabindex="-1" aria-labelledby="notificationModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title text-primary" id="notificationModalLabel">
                            <i class="bi bi-bell"></i> {{__('Add notification')}}
                        </h5>
                        <button type="button" class="btn btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body">
                        <p>
                            Ajouter une notification pour la plage horaire suivante :
                            <strong id="horaireSelection" class="text-success"></strong>.
                        </p>

                        <!-- Type de notification -->
                        <div class="form-check mb-3">
                            <input class="form-check-input" type="radio" name="typeNotification" id="smsOption" value="SMS" checked>
                            <label class="form-check-label" for="smsOption">
                                <i class="bi bi-chat-left-text"></i> SMS
                            </label>
                        </div>
                        <div id="smsField" class="mt-2">
                            <label for="smsInput" class="form-label">{{__('Phone number')}} :</label>
                            <input
                                type="tel"
                                id="smsInput"
                                class="form-control"
                                placeholder="+33 6 12 34 56 78"
                                value="{{ Auth::user()->numTel }}"
                            >
                        </div>

                        <div class="form-check mb-3 mt-4">
                            <input class="form-check-input" type="radio" name="typeNotification" id="mailOption" value="Mail">
                            <label class="form-check-label" for="mailOption">
                                <i class="bi bi-envelope"></i> Email
                            </label>
                        </div>
                        <div id="mailField" class="mt-2" style="display: none;">
                            <label for="mailInput" class="form-label">{{__('Email Address')}}</label>
                            <input
                                type="email"
                                id="mailInput"
                                class="form-control"
                                placeholder="exemple@domaine.com"
                                value="{{ Auth::user()->email }}"
                            >
                        </div>

                        <!-- Durée avant rappel -->
                        <label for="duree" class="form-label mt-3">Durée avant rendez-vous :</label>
                        <select id="duree" class="form-select">
                            <option value="1jour">1 jour</option>
                            <option value="2jours">2 jours</option>
                            <option value="1semaine">1 semaine</option>
                        </select>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" id="goBackBtn">{{__('Back')}}</button>
                        <button type="button" class="btn btn-primary" id="saveNotificationBtn">{{__('Validate')}}</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="{{ asset('js/reservation.js') }}"></script>
@endsection
