@extends('layouts.app')
@include('base')

@section('content')
    <div class="container">

        <!-- Navigation de retour -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <!--
                Change the back route as you like:
                e.g. route('reservation.index') or route('entreprise.activites', ['entreprise' => $entreprise->id])
            -->
            <a href="{{ route('reservation.index') }}" class="btn btn-outline-secondary mt-4 mb-4">
                <i class="bi bi-arrow-left"></i>
            </a>

            <!-- Nom de l'entreprise au centre -->
            <h1 class="text-center flex-grow-1">{{ $entreprise->libelle }}</h1>
        </div>

        <!-- Ancienne réservation -->
        <div class="alert alert-info">
            <strong>Ancienne réservation :</strong><br>
            Date : <em>{{ $reservation->dateRdv->format('d/m/Y') }}</em><br>
            Heure : <em>{{ \Carbon\Carbon::parse($reservation->heureDeb)->format('H:i') }} -
                {{ \Carbon\Carbon::parse($reservation->heureFin)->format('H:i') }}</em><br>
            Nombre de personnes : <em>{{ $reservation->nbPersonnes }}</em>
        </div>

        <!-- Tableau des disponibilités (même style que create.blade.php) -->
        <div class="availability">
            <h4 class="text-center mb-4">Modifier la réservation pour l’activité: {{ $activite->libelle }}</h4>

            <ul class="list-unstyled">
                @if ($activite->plages->count() > 0)
                    @foreach ($activite->plages->groupBy('datePlage') as $date => $plages)
                        <li class="mb-4">
                            <h5 class="text-primary">
                                {{ \Carbon\Carbon::parse($date)->isoFormat('dddd D MMMM YYYY') }}
                            </h5>

                            <div class="d-flex flex-wrap gap-2">
                                @foreach ($plages as $plage)
                                    @php
                                        try {
                                            $heureDeb = \Carbon\Carbon::parse($plage->heureDeb);
                                            $heureFin = \Carbon\Carbon::parse($plage->heureFin);
                                            $interval = \Carbon\Carbon::parse($plage->interval)->hour * 60
                                                      + \Carbon\Carbon::parse($plage->interval)->minute;
                                        } catch (\Exception $e) {
                                            echo '<div class="text-danger">Erreur de formatage des plages horaires.</div>';
                                            continue;
                                        }
                                    @endphp

                                    @while ($heureDeb->lessThan($heureFin))
                                        @php
                                            // Intervalle courant
                                            $currentStart = \Carbon\Carbon::parse($date)
                                                ->setTimeFromTimeString($heureDeb->format('H:i:s'));
                                            $currentEnd   = $currentStart->copy()->addMinutes($interval);

                                            // Vérifier si ce créneau est déjà réservé
                                            $isReserved = $reservations->contains(function($res) use ($currentStart, $currentEnd) {
                                                // Compare date
                                                if ($res->dateRdv->format('Y-m-d') !== $currentStart->format('Y-m-d')) {
                                                    return false;
                                                }
                                                // Compare horaire (chevauchement)
                                                $resStart = \Carbon\Carbon::createFromFormat(
                                                    'Y-m-d H:i:s',
                                                    $res->dateRdv->format('Y-m-d').' '.$res->heureDeb
                                                );
                                                $resEnd   = \Carbon\Carbon::createFromFormat(
                                                    'Y-m-d H:i:s',
                                                    $res->dateRdv->format('Y-m-d').' '.$res->heureFin
                                                );
                                                return $currentStart->lt($resEnd) && $currentEnd->gt($resStart);
                                            });
                                        @endphp

                                        @if (! $isReserved)
                                            <!-- Afficher le bouton d'édition -->
                                            <button
                                                class="btn btn-outline-primary flex-grow-1 horaire-btn"
                                                data-bs-toggle="modal"
                                                data-bs-target="#editModal"
                                                data-horaire="{{ $currentStart->format('H:i') }} - {{ $currentEnd->format('H:i') }}"
                                                data-date="{{ $currentStart->format('Y-m-d') }}"
                                            >
                                                {{ $currentStart->format('H:i') }} - {{ $currentEnd->format('H:i') }}
                                            </button>
                                        @endif

                                        @php
                                            // Prochain intervalle
                                            $heureDeb->addMinutes($interval);
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

        <!-- MODAL : Modifier la réservation -->
        <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <!-- Le formulaire pointe vers update -->
                <form action="{{ route('reservation.update', $reservation->id) }}" method="POST" id="editForm">
                    @csrf
                    @method('PUT') {{-- ou @method('PATCH') --}}

                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title text-primary" id="editModalLabel">
                                <i class="bi bi-calendar-check"></i> Modifier la réservation
                            </h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>

                        <div class="modal-body">
                            <p>
                                Vous êtes sur le point de modifier votre réservation pour le créneau :
                                <strong id="selectedHoraire" class="text-success"></strong>
                                le <strong id="selectedDate" class="text-primary"></strong>.
                            </p>

                            <!-- Champ caché pour slot (ex: "09:00 - 10:00|2025-02-01") -->
                            <input type="hidden" name="slot" id="hiddenSlot">

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
                                        value="{{ $reservation->nbPersonnes }}"
                                        min="1"
                                    >
                                </div>
                            @endif

                            <!-- Partie Notifications (optionnelle) -->
                            <h5 class="mt-4">Notifications ajoutées :</h5>
                            <ul id="notificationsList" class="list-group"></ul>

                            <button type="button" class="btn btn-success w-100 mt-3" id="addNotificationBtn"
                                    data-bs-toggle="modal" data-bs-target="#notificationModal">
                                <i class="bi bi-plus-circle"></i> Ajouter une notification
                            </button>
                        </div>

                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary w-100">Confirmer la modification</button>
                            <button type="button" class="btn btn-secondary w-100" data-bs-dismiss="modal">Annuler</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- MODAL : Ajouter une notification (même style que create) -->
        <div class="modal fade" id="notificationModal" tabindex="-1" aria-labelledby="notificationModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title text-primary" id="notificationModalLabel">
                            <i class="bi bi-bell"></i> Ajouter une notification
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
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
                            <label for="smsInput" class="form-label">Numéro de téléphone :</label>
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
                            <label for="mailInput" class="form-label">Adresse email :</label>
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
                        <button type="button" class="btn btn-secondary" id="goBackBtn">Précédent</button>
                        <button type="button" class="btn btn-primary" id="saveNotificationBtn">Valider</button>
                    </div>
                </div>
            </div>
        </div>
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $message)
                        <li>{{ $message }}</li>
                    @endforeach
                </ul>
            </div>
        @endif


    </div>

    <!-- Optionally reuse the same reservation.js (adapt the IDs if needed) -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Quand on clique sur un bouton de créneau
            document.querySelectorAll('.horaire-btn').forEach(button => {
                button.addEventListener('click', () => {
                    const horaire = button.dataset.horaire; // ex: "09:00 - 10:00"
                    const date = button.dataset.date;       // ex: "2025-02-01"

                    // Injecter dans la modale
                    document.getElementById('selectedHoraire').textContent = horaire;
                    document.getElementById('selectedDate').textContent = date;

                    // Construire la value pour "slot" (même format que dans update())
                    // "09:00 - 10:00|2025-02-01"
                    const slotValue = horaire + '|' + date;
                    document.getElementById('hiddenSlot').value = slotValue;

                    // Vider la liste de notifications (si besoin)
                    document.getElementById('notificationsList').innerHTML = '';
                });
            });

            // Gestion toggle SMS/Email
            const smsOption = document.getElementById('smsOption');
            const mailOption = document.getElementById('mailOption');
            const smsField   = document.getElementById('smsField');
            const mailField  = document.getElementById('mailField');

            smsOption.addEventListener('change', () => {
                smsField.style.display  = smsOption.checked ? 'block' : 'none';
                mailField.style.display = 'none';
            });
            mailOption.addEventListener('change', () => {
                mailField.style.display = mailOption.checked ? 'block' : 'none';
                smsField.style.display  = 'none';
            });

            // Bouton "Add Notification" => ouvre le modal (#notificationModal)
            // Quand on clique sur "Valider" dans #notificationModal => on ajoute une ligne dans #notificationsList
            const saveNotificationBtn = document.getElementById('saveNotificationBtn');
            saveNotificationBtn.addEventListener('click', () => {
                const type = smsOption.checked ? 'SMS' : 'Mail';
                const contenu = smsOption.checked
                    ? document.getElementById('smsInput').value
                    : document.getElementById('mailInput').value;
                const duree = document.getElementById('duree').value;

                // On ajoute un <li> dans la liste
                const notifList = document.getElementById('notificationsList');
                const li = document.createElement('li');
                li.className = 'list-group-item';
                li.textContent = `${type} -> ${contenu} (${duree} avant)`;
                notifList.appendChild(li);

                // On crée un input hidden pour chaque notification, comme "create" le fait
                // ex: notifications[0][typeNotification], notifications[0][contenu], ...
                // Comptons le nombre de notifications existantes pour l'index
                const index = document.querySelectorAll('#notificationsList li').length - 1;

                // On injecte les inputs hidden dans le formulaire #editForm
                const form = document.getElementById('editForm');

                // typeNotification
                let inputType = document.createElement('input');
                inputType.type = 'hidden';
                inputType.name = `notifications[${index}][typeNotification]`;
                inputType.value = type;
                form.appendChild(inputType);

                // contenu
                let inputContenu = document.createElement('input');
                inputContenu.type = 'hidden';
                inputContenu.name = `notifications[${index}][contenu]`;
                inputContenu.value = contenu;
                form.appendChild(inputContenu);

                // duree
                let inputDuree = document.createElement('input');
                inputDuree.type = 'hidden';
                inputDuree.name = `notifications[${index}][duree]`;
                inputDuree.value = duree;
                form.appendChild(inputDuree);

                // Fermer la modal
                const notificationModal = bootstrap.Modal.getInstance(document.getElementById('notificationModal'));
                notificationModal.hide();
            });

            // Bouton "Précédent"
            document.getElementById('goBackBtn').addEventListener('click', () => {
                const notificationModal = bootstrap.Modal.getInstance(document.getElementById('notificationModal'));
                notificationModal.hide();
            });
        });
    </script>
@endsection
