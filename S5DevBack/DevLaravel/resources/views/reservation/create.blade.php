@extends('base')

@section('content')
<div class="container">
    <!-- Navigation de retour -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <a href="/" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left"></i>
        </a>
        <h1 class="text-center flex-grow-1">{{ $entreprise->libelle }}</h1>
    </div>

    <!-- Tableau des disponibilités -->
    <div class="availability">
        <h4 class="text-center mb-4">Semaine X</h4>

        <!-- Liste des jours avec horaires affichés -->
        <ul class="list-unstyled">
            <!-- Lundi -->
            <li class="border-top py-3">
                <div class="d-flex justify-content-between align-items-center">
                    <span>Lundi 18 novembre 2024</span>
                </div>
                <div class="mt-2">
                    <div class="d-flex justify-content-around">
                        <button class="btn btn-outline-primary horaire-btn" data-bs-toggle="modal" data-bs-target="#horaireModal" data-horaire="9h">9h</button>
                        <button class="btn btn-outline-primary horaire-btn" data-bs-toggle="modal" data-bs-target="#horaireModal" data-horaire="10h">10h</button>
                        <button class="btn btn-outline-primary horaire-btn" data-bs-toggle="modal" data-bs-target="#horaireModal" data-horaire="14h">14h</button>
                        <button class="btn btn-outline-primary horaire-btn" data-bs-toggle="modal" data-bs-target="#horaireModal" data-horaire="15h">15h</button>
                        <button class="btn btn-outline-primary horaire-btn" data-bs-toggle="modal" data-bs-target="#horaireModal" data-horaire="16h">16h</button>
                        <button class="btn btn-outline-primary horaire-btn" data-bs-toggle="modal" data-bs-target="#horaireModal" data-horaire="17h">17h</button>
                        <button class="btn btn-outline-primary horaire-btn" data-bs-toggle="modal" data-bs-target="#horaireModal" data-horaire="18h">18h</button>
                    </div>
                </div>
            </li>
            <!-- Mardi -->
            <li class="border-top py-3">
                <div class="d-flex justify-content-between align-items-center">
                    <span>Mardi 19 novembre 2024</span>
                </div>
                <div class="mt-2">
                    <div class="d-flex justify-content-around">
                        <button class="btn btn-outline-primary horaire-btn" data-bs-toggle="modal" data-bs-target="#horaireModal" data-horaire="9h">9h</button>
                        <button class="btn btn-outline-primary horaire-btn" data-bs-toggle="modal" data-bs-target="#horaireModal" data-horaire="10h">10h</button>
                        <button class="btn btn-outline-primary horaire-btn" data-bs-toggle="modal" data-bs-target="#horaireModal" data-horaire="14h">14h</button>
                        <button class="btn btn-outline-primary horaire-btn" data-bs-toggle="modal" data-bs-target="#horaireModal" data-horaire="15h">15h</button>
                        <button class="btn btn-outline-primary horaire-btn" data-bs-toggle="modal" data-bs-target="#horaireModal" data-horaire="16h">16h</button>
                        <button class="btn btn-outline-primary horaire-btn" data-bs-toggle="modal" data-bs-target="#horaireModal" data-horaire="17h">17h</button>
                        <button class="btn btn-outline-primary horaire-btn" data-bs-toggle="modal" data-bs-target="#horaireModal" data-horaire="18h">18h</button>
                    </div>
                </div>
            </li>
            <!-- Mercredi -->
            <li class="border-top py-3">
                <div class="d-flex justify-content-between align-items-center">
                    <span>Mercredi 20 novembre 2024</span>
                </div>
                <div class="mt-2">
                    <div class="d-flex justify-content-around">
                        <button class="btn btn-outline-primary horaire-btn" data-bs-toggle="modal" data-bs-target="#horaireModal" data-horaire="9h">9h</button>
                        <button class="btn btn-outline-primary horaire-btn" data-bs-toggle="modal" data-bs-target="#horaireModal" data-horaire="10h">10h</button>
                        <button class="btn btn-outline-primary horaire-btn" data-bs-toggle="modal" data-bs-target="#horaireModal" data-horaire="14h">14h</button>
                        <button class="btn btn-outline-primary horaire-btn" data-bs-toggle="modal" data-bs-target="#horaireModal" data-horaire="15h">15h</button>
                        <button class="btn btn-outline-primary horaire-btn" data-bs-toggle="modal" data-bs-target="#horaireModal" data-horaire="16h">16h</button>
                        <button class="btn btn-outline-primary horaire-btn" data-bs-toggle="modal" data-bs-target="#horaireModal" data-horaire="17h">17h</button>
                        <button class="btn btn-outline-primary horaire-btn" data-bs-toggle="modal" data-bs-target="#horaireModal" data-horaire="18h">18h</button>
                    </div>
                </div>
            </li>
            <!-- Jeudi -->
            <li class="border-top py-3">
                <div class="d-flex justify-content-between align-items-center">
                    <span>Jeudi 21 novembre 2024</span>
                </div>
                <div class="mt-2">
                    <div class="d-flex justify-content-around">
                        <button class="btn btn-outline-primary horaire-btn" data-bs-toggle="modal" data-bs-target="#horaireModal" data-horaire="9h">9h</button>
                        <button class="btn btn-outline-primary horaire-btn" data-bs-toggle="modal" data-bs-target="#horaireModal" data-horaire="10h">10h</button>
                        <button class="btn btn-outline-primary horaire-btn" data-bs-toggle="modal" data-bs-target="#horaireModal" data-horaire="14h">14h</button>
                        <button class="btn btn-outline-primary horaire-btn" data-bs-toggle="modal" data-bs-target="#horaireModal" data-horaire="15h">15h</button>
                        <button class="btn btn-outline-primary horaire-btn" data-bs-toggle="modal" data-bs-target="#horaireModal" data-horaire="16h">16h</button>
                        <button class="btn btn-outline-primary horaire-btn" data-bs-toggle="modal" data-bs-target="#horaireModal" data-horaire="17h">17h</button>
                        <button class="btn btn-outline-primary horaire-btn" data-bs-toggle="modal" data-bs-target="#horaireModal" data-horaire="18h">18h</button>
                    </div>
                </div>
            </li>
            <!-- Vendredi -->
            <li class="border-top py-3">
                <div class="d-flex justify-content-between align-items-center">
                    <span>Vendredi 22 novembre 2024</span>
                </div>
                <div class="mt-2">
                    <div class="d-flex justify-content-around">
                        <button class="btn btn-outline-primary horaire-btn" data-bs-toggle="modal" data-bs-target="#horaireModal" data-horaire="9h">9h</button>
                        <button class="btn btn-outline-primary horaire-btn" data-bs-toggle="modal" data-bs-target="#horaireModal" data-horaire="10h">10h</button>
                        <button class="btn btn-outline-primary horaire-btn" data-bs-toggle="modal" data-bs-target="#horaireModal" data-horaire="14h">14h</button>
                        <button class="btn btn-outline-primary horaire-btn" data-bs-toggle="modal" data-bs-target="#horaireModal" data-horaire="15h">15h</button>
                        <button class="btn btn-outline-primary horaire-btn" data-bs-toggle="modal" data-bs-target="#horaireModal" data-horaire="16h">16h</button>
                        <button class="btn btn-outline-primary horaire-btn" data-bs-toggle="modal" data-bs-target="#horaireModal" data-horaire="17h">17h</button>
                        <button class="btn btn-outline-primary horaire-btn" data-bs-toggle="modal" data-bs-target="#horaireModal" data-horaire="18h">18h</button>
                    </div>
                </div>
            </li>
            <!-- Samedi -->
            <li class="border-top py-3">
                <div class="d-flex justify-content-between align-items-center">
                    <span>Samedi 23 novembre 2024</span>
                </div>
                <div class="mt-2">
                    <div class="d-flex justify-content-around">
                        <button class="btn btn-outline-primary horaire-btn" data-bs-toggle="modal" data-bs-target="#horaireModal" data-horaire="9h">9h</button>
                        <button class="btn btn-outline-primary horaire-btn" data-bs-toggle="modal" data-bs-target="#horaireModal" data-horaire="10h">10h</button>
                        <button class="btn btn-outline-primary horaire-btn" data-bs-toggle="modal" data-bs-target="#horaireModal" data-horaire="14h">14h</button>
                        <button class="btn btn-outline-primary horaire-btn" data-bs-toggle="modal" data-bs-target="#horaireModal" data-horaire="15h">15h</button>
                        <button class="btn btn-outline-primary horaire-btn" data-bs-toggle="modal" data-bs-target="#horaireModal" data-horaire="16h">16h</button>
                        <button class="btn btn-outline-primary horaire-btn" data-bs-toggle="modal" data-bs-target="#horaireModal" data-horaire="17h">17h</button>
                        <button class="btn btn-outline-primary horaire-btn" data-bs-toggle="modal" data-bs-target="#horaireModal" data-horaire="18h">18h</button>
                    </div>
                </div>
            </li>
        </ul>
    </div>

    <!-- Modal pour l'horaire -->
    <div class="modal fade" id="horaireModal" tabindex="-1" aria-labelledby="horaireModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="horaireModalLabel">Sélectionner l'heure</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Vous avez sélectionné l'horaire : <span id="horaireSelection"></span></p>
                    <!-- Ici tu peux ajouter un formulaire ou des informations supplémentaires -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                    <button type="button" class="btn btn-primary">Confirmer</button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // JavaScript pour gérer l'affichage de l'horaire sélectionné dans la modal
    document.querySelectorAll('.horaire-btn').forEach(button => {
        button.addEventListener('click', function() {
            const horaire = this.getAttribute('data-horaire');
            document.getElementById('horaireSelection').textContent = horaire;
        });
    });
</script>
@endsection
