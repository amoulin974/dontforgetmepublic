@extends('base')

@section('content')
<div class="container">
    <!-- Navigation de retour -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <a href="/entreprise/{{$entreprise->id}}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left"></i>
        </a>
        <h1 class="text-center flex-grow-1">{{ $entreprise->libelle }}</h1>
    </div>

    <!-- Tableau des disponibilités -->
    <div class="availability">
        <h4 class="text-center mb-4">Disponibilités de la semaine</h4>

        <!-- Liste des jours de la semaine -->
        @php
            $today = \Carbon\Carbon::today();
            $endOfWeek = $today->copy()->addDays(6); // Jusqu'à samedi
        @endphp

        <ul class="list-unstyled">
            @for ($date = $today; $date <= $endOfWeek; $date->addDay())
                @if (!$date->isSunday()) <!-- Exclut le dimanche -->
                    <li class="mb-4">
                        <h5 class="text-primary">{{ $date->isoFormat('dddd D MMMM YYYY') }}</h5>

                        <!-- Plages horaires disponibles -->
                        <div class="d-flex flex-wrap gap-2">
                            @if ($entreprise->plages->isNotEmpty())
                                @foreach ($entreprise->plages as $plage)
                                    @php
                                        $heureDeb = \Carbon\Carbon::parse($plage->heureDeb);
                                        $heureFin = \Carbon\Carbon::parse($plage->heureFin);
                                        $interval = \Carbon\Carbon::parse($plage->interval)->minute;
                                    @endphp

                                    <script>console.log('{{$plage}}');</script>

                                    @while ($heureDeb->lessThan($heureFin))
                                        <button 
                                            class="btn btn-outline-primary flex-grow-1 horaire-btn" 
                                            data-horaire="{{ $heureDeb->format('H:i') }} - {{ $heureDeb->copy()->addMinutes($interval)->format('H:i') }}"
                                            data-date="{{ $date->format('Y-m-d') }}"
                                        >
                                            {{ $heureDeb->format('H:i') }} - {{ $heureDeb->copy()->addMinutes($interval)->format('H:i') }}
                                        </button>
                                        @php
                                            $heureDeb->addMinutes($interval);
                                        @endphp
                                    @endwhile 
                                @endforeach
                            @else
                                <p>Aucune plage horaire disponible.</p>
                            @endif
                        </div>
                    </li>
                @endif
            @endfor
        </ul>
    </div>

    <!-- MODAL 1 : Réservation -->
    <div class="modal fade" id="reservationModal" tabindex="-1" aria-labelledby="reservationModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form action="{{ route('reservation.store') }}" method="POST" id="reservationForm">
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
                            <strong id="selectedHoraire" class="text-success"></strong>.
                        </p>

                        <!-- Champs cachés pour la date et l'horaire -->
                        <input type="hidden" name="dateRdv" id="hiddenDateRdv">
                        <input type="hidden" name="horaire" id="hiddenHoraire">

                        <!-- Nombre de personnes -->
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

                        <!-- Liste des notifications ajoutées -->
                        <h5 class="mt-4">Notifications ajoutées :</h5>
                        <ul id="notificationsList" class="list-group">
                            <!-- Les notifications ajoutées s'afficheront ici -->
                        </ul>

                        <!-- Bouton : Ajouter une nouvelle notification -->
                        <button type="button" class="btn btn-success w-100 mt-3" id="addNotificationBtn">
                            <i class="bi bi-plus-circle"></i> Ajouter une notification
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
                        <i class="bi bi-bell"></i> Ajouter une notification
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>
                        Ajouter une notification pour la plage horaire suivante :
                        <strong id="horaireSelection" class="text-success"></strong>.
                    </p>

                    <!-- Type de notification -->
                    <div class="form-check mb-3">
                        <input class="form-check-input" type="radio" name="typeNotification" id="smsOption" value="SMS" checked>
                        <label class="form-check-label" for="smsOption"><i class="bi bi-chat-left-text"></i> SMS</label>
                    </div>
                    <div id="smsField" class="mt-2">
                        <label for="smsInput" class="form-label">Numéro de téléphone :</label>
                        <input 
                            type="tel" 
                            id="smsInput" 
                            class="form-control" 
                            placeholder="+33 6 50 49 60 22"
                            value="{{ Auth::user()->numTel }}"
                        >
                    </div>

                    <div class="form-check mb-3 mt-4">
                        <input class="form-check-input" type="radio" name="typeNotification" id="mailOption" value="Mail">
                        <label class="form-check-label" for="mailOption"><i class="bi bi-envelope"></i> Email</label>
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
                    <label for="duree" class="form-label mt-3">Durée avant rappel :</label>
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
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    let selectedHoraire = '';
    let selectedDate = '';
    const notificationsList = document.getElementById('notificationsList');
    const reservationForm = document.getElementById('reservationForm');

    // Clic sur un horaire
    document.querySelectorAll('.horaire-btn').forEach(button => {
        button.addEventListener('click', function () {
            selectedDate = this.getAttribute('data-date') + ' 00:00:00';
            selectedHoraire = this.getAttribute('data-horaire');

            document.getElementById('hiddenHoraire').value = selectedHoraire;
            document.getElementById('hiddenDateRdv').value = selectedDate;
            document.getElementById('selectedHoraire').textContent = selectedHoraire;

            const reservationModal = new bootstrap.Modal(document.getElementById('reservationModal'));
            reservationModal.show();
        });
    });

    // Ajouter une notification
    document.getElementById('addNotificationBtn').addEventListener('click', function () {
        const reservationModal = bootstrap.Modal.getInstance(document.getElementById('reservationModal'));
        reservationModal.hide();

        const notificationModal = new bootstrap.Modal(document.getElementById('notificationModal'));
        notificationModal.show();
    });

    // Retour à la modal de réservation
    document.getElementById('goBackBtn').addEventListener('click', function () {
        const notificationModal = bootstrap.Modal.getInstance(document.getElementById('notificationModal'));
        notificationModal.hide();

        const reservationModal = new bootstrap.Modal(document.getElementById('reservationModal'));
        reservationModal.show();
    });

    // Validation d'une notification
    document.getElementById('saveNotificationBtn').addEventListener('click', function () {
        const typeNotification = document.querySelector('input[name="typeNotification"]:checked').value;
        const contenu = typeNotification === 'SMS' 
            ? document.getElementById('smsInput').value 
            : document.getElementById('mailInput').value;
        const duree = document.getElementById('duree').value;

        if (contenu.trim() === '') {
            alert('Veuillez renseigner le contenu de la notification.');
            return;
        }

        // Ajouter une notification à la liste
        const notificationItem = document.createElement('li');
        notificationItem.className = 'list-group-item';
        notificationItem.textContent = `${typeNotification} - ${contenu} - Rappel : ${duree}`;
        notificationsList.appendChild(notificationItem);

        // Ajouter un input caché pour chaque notification
        ['typeNotification', 'contenu', 'duree'].forEach((key) => {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = `notifications[${notificationsList.children.length - 1}][${key}]`;
            input.value = key === 'contenu' ? contenu : key === 'typeNotification' ? typeNotification : duree;
            reservationForm.appendChild(input);
        });

        const notificationModal = bootstrap.Modal.getInstance(document.getElementById('notificationModal'));
        notificationModal.hide();

        const reservationModal = new bootstrap.Modal(document.getElementById('reservationModal'));
        reservationModal.show();
    });

    // Gestion des champs conditionnels (SMS/Email)
    const smsOption = document.getElementById('smsOption');
    const mailOption = document.getElementById('mailOption');
    const smsField = document.getElementById('smsField');
    const mailField = document.getElementById('mailField');

    smsOption.addEventListener('change', () => {
        smsField.style.display = 'block';
        mailField.style.display = 'none';
    });

    mailOption.addEventListener('change', () => {
        mailField.style.display = 'block';
        smsField.style.display = 'none';
    });
});
</script>
@endsection
