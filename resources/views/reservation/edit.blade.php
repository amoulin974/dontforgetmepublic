@extends('layouts.app')

@section('content')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script defer src="{{ asset('js/reservation.js') }}"></script>

    <div class="container">
        <!-- Navigation de retour -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <a href="{{ route('entreprise.activites', ['entreprise' => $entreprise->id]) }}" class="btn btn-outline-secondary mt-4 mb-4">
                <i class="bi bi-arrow-left"></i>
            </a>
            <h1 class="text-center flex-grow-1">{{ $entreprise->libelle }}</h1>
        </div>

        <!-- Tableau des disponibilités -->
        <div class="availability">
            <h4 class="text-center mb-4">{{__('Available slots for ')}}{{ $activite->libelle }}</h4>

            @if ($entreprise->travailler_users->where('pivot.idActivite', $activite->id)->where('pivot.statut', '!=', 'Invité')->count() > 1)
                <label for="employe" class="form-label mt-3">{{__('Please select an employee')}}</label>
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
                                            continue;
                                        }
                                    @endphp

                                    @while ($heureDeb->lessThan($heureFin))
                                        @php
                                            $currentStart = $plage->datePlage->copy()->setTimeFromTimeString($heureDeb->format('H:i:s'));
                                            $currentEnd = $currentStart->copy()->addMinutes($interval);
                                            $isReserved = $reservations->contains(function($res) use ($date, $currentStart, $currentEnd) {
                                                if ($res->dateRdv->format('Y-m-d 00:00:00') !== $date) {
                                                    return false;
                                                }
                                                $resStart = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $res->dateRdv->format('Y-m-d').' '.$res->heureDeb);
                                                $resEnd = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $res->dateRdv->format('Y-m-d').' '.$res->heureFin);
                                                return $currentStart->lt($resEnd) && $currentEnd->gt($resStart);
                                            });
                                        @endphp

                                        @if (! $isReserved)
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

                                        @php
                                            $heureDeb->addMinutes($interval);
                                        @endphp
                                    @endwhile
                                @endforeach
                            </div>
                        </li>
                    @endforeach
                @else
                    <p>{{__('No available time slot.')}}</p>
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
                                <i class="bi bi-calendar-check"></i> {{__('Book a time slot')}}
                            </h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <p>
                                {{__("You're about to book for the following time slot")}} :
                                <strong id="selectedHoraire" class="text-success"></strong>
                                le <strong id="selectedDate" class="text-primary"></strong>.
                            </p>

                            <input type="hidden" name="dateRdv" id="hiddenDateRdv">
                            <input type="hidden" name="horaire" id="hiddenHoraire">

                            @if ($entreprise->travailler_users->where('pivot.idActivite', $activite->id)->where('pivot.statut', '!=', 'Invité')->count() > 1)
                                <div class="form-group mb-3">
                                    <label for="employeSelect" class="form-label">{{__("Select an employee")}} :</label>
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
                                <p>{{__('Automatically assigned employee')}} : <strong>{{ $employe->nom }} {{ $employe->prenom }}</strong></p>
                            @endif

                            @if ($entreprise->typeRdv[0] == 1)
                                <div class="form-group mb-3">
                                    <label for="nbPersonnes" class="form-label">
                                        <i class="bi bi-people-fill"></i> {{__("Amount of people")}} :
                                    </label>
                                    <input
                                        type="number"
                                        name="nbPersonnes"
                                        id="nbPersonnes"
                                        class="form-control"
                                        placeholder="{{('Enter the amount of people')}}"
                                        min="1"
                                        required
                                    >
                                </div>
                            @endif

                            <h5 class="mt-4">{{__("Added notifications")}} :</h5>
                            <ul id="notificationsList" class="list-group"></ul>

                            <button type="button" class="btn btn-success w-100 mt-3" id="addNotificationBtn" data-bs-toggle="modal" data-bs-target="#notificationModal">
                                <i class="bi bi-plus-circle"></i> {{__('Add notification')}}
                            </button>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary w-100">{{__("Confirm booking")}}</button>
                            <button type="button" class="btn btn-secondary w-100" data-bs-dismiss="modal">{{__('Cancel')}}</button>
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
                        <p>{{__("Add a notification for the following time slot")}} : <strong id="horaireSelection" class="text-success"></strong>.</p>

                        <div class="form-check mb-3">
                            <input class="form-check-input" type="radio" name="typeNotification" id="smsOption" value="SMS" checked>
                            <label class="form-check-label" for="smsOption">
                                <i class="bi bi-chat-left-text"></i> SMS
                            </label>
                        </div>
                        <div id="smsField" class="mt-2">
                            <label for="smsInput" class="form-label">{{__('Phone number')}} :</label>
                            <input type="tel" id="smsInput" class="form-control" placeholder="+33 6 12 34 56 78" value="{{ Auth::user()->numTel }}">
                        </div>

                        <div class="form-check mb-3 mt-4">
                            <input class="form-check-input" type="radio" name="typeNotification" id="mailOption" value="Mail">
                            <label class="form-check-label" for="mailOption">
                                <i class="bi bi-envelope"></i> Email
                            </label>
                        </div>
                        <div id="mailField" class="mt-2" style="display: none;">
                            <label for="mailInput" class="form-label">{{__('Email Address')}} :</label>
                            <input type="email" id="mailInput" class="form-control" placeholder="exemple@domaine.com" value="{{ Auth::user()->email }}">
                        </div>

                        <label for="duree" class="form-label mt-3">{{__('Time before appointment')}} :</label>
                        <select id="duree" class="form-select">
                            <option value="1jour">{{__("1 day")}}</option>
                            <option value="2jours">{{__("2 days")}}</option>
                            <option value="1semaine">{{__("1 week")}}</option>
                        </select>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" id="goBackBtn">{{__('Previous')}}</button>
                        <button type="button" class="btn btn-primary" id="saveNotificationBtn">{{__('Validate')}}</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
