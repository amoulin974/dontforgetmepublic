@extends('layouts.app')

@section('title', __('Choose appointment type'))

@section('content')

<div class="container d-flex flex-column align-items-center" style="min-height: 100vh;">
    <div class="d-flex justify-content-between align-items-center w-100" style="position: relative;">
        <a class="btn btn-outline-secondary mt-5 mb-5" id="retour" redirectUrl="{{ route('entreprise.create') }}" style="position: absolute; left: 0;">
            <i class="bi bi-arrow-left"></i>
        </a>

        <div class="d-flex justify-content-center align-items-center w-100 mt-5 mb-5">
            <span class="me-2 bg-primary rounded-circle" style="width: 12px; height: 12px;"></span>
            <span class="mx-2 bg-primary rounded-circle" style="width: 12px; height: 12px;"></span>
        </div>
    </div>

        {{-- Barre de progression --}}
        <div class="progress mb-4" style="height: 20px; width: 100%;">
            <div id="progress-bar" class="progress-bar bg-primary" role="progressbar"
                style="width: 20%;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">
            </div>
        </div>


        {{-- Étape 1 : Nombre de clients par créneau --}}
        <div class="step" id="step1">
            <h2 class="text-center mb-4 fw-bold">{{__('Do you treat one or several clients per schedule?')}}</h2>
            <div class="row text-center w-100">
                {{-- Carte pour "Un seul client" --}}
                <div class="col-md-6 mb-4">
                    <div class="card border border-danger shadow-sm h-100 w-100" style="cursor: pointer;">
                        <button class="btn btn-nav w-100 py-4 d-flex flex-column align-items-center" style="border: none;" answer="0">
                            <div class="card-body d-flex flex-column align-items-center">
                                <img src="{{ asset('images/Consulting.png') }}" alt="Un seul client" style="width: 100px; height: auto;">
                                <p class="mt-3 fw-bold text-dark">{{__('One')}} ({{__('ex: Lawyer')}})</p>
                            </div>
                        </button>
                    </div>
                </div>

                {{-- Carte pour "Plusieurs clients" --}}
                <div class="col-md-6 mb-4">
                    <div class="card border border-danger shadow-sm h-100 w-100" style="cursor: pointer;">
                        <button class="btn btn-nav w-100 py-4 d-flex flex-column align-items-center" id="manyClients" style="border: none;" answer="1">
                            <div class="card-body d-flex flex-column align-items-center">
                                <img src="{{ asset('images/Restaurant.png') }}" alt="Plusieurs clients" style="width: 100px; height: auto;">
                                <p class="mt-3 fw-bold text-dark">{{__('Several')}} ({{__('ex: Hairdresser, Restaurant')}})</p>
                            </div>
                        </button>
                    </div>
                </div>
            </div>
        </div>


        {{-- Etape parallèle : Définition de la capacité maximale de l'entreprise --}}
        <!-- Modale pour définir la capacité max -->
        <div class="modal fade" id="capacityModal" tabindex="-1" aria-labelledby="capacityModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="capacityModalLabel">Définir la capacité maximale</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <label for="capacityInput" class="form-label">Capacité maximale :</label>
                        <input type="number" id="capacityInput" class="form-control" min="1" required>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary" id="confirmCapacity">Confirmer</button>
                    </div>
                </div>
            </div>
        </div>



        {{-- Étape 2 : Qui sélectionne le créneau ? --}}
        <div class="step d-none" id="step2">
            <h2 class="text-center mb-4 fw-bold">{{__('Who chooses the schedule?')}}</h2>
            <div class="row text-center w-100">
                {{-- Carte pour "Seulement vous" --}}
                <div class="col-md-6 mb-4">
                    <div class="card border border-danger shadow-sm h-100 w-100" style="cursor: pointer;">
                        <button class="btn btn-nav w-100 py-4 d-flex flex-column align-items-center" style="border: none;" answer="0">
                            <div class="card-body d-flex flex-column align-items-center">
                                <img src="{{ asset('images/Booking.png') }}" alt="Seulement vous" style="width: 100px; height: auto;">
                                <p class="mt-3 fw-bold text-dark">{{__('You only')}}</p>
                            </div>
                        </button>
                    </div>
                </div>

                {{-- Carte pour "Le client et vous" --}}
                <div class="col-md-6 mb-4">
                    <div class="card border border-danger shadow-sm h-100 w-100" style="cursor: pointer;">
                        <button class="btn btn-nav w-100 py-4 d-flex flex-column align-items-center" style="border: none;" answer="1">
                            <div class="card-body d-flex flex-column align-items-center">
                                <img src="{{ asset('images/BusinessDecision.png') }}" alt="Le client et vous" style="width: 100px; height: auto;">
                                <p class="mt-3 fw-bold text-dark">{{__('You and the client')}}</p>
                            </div>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        {{-- Étape 3 : Affectez-vous un salarié pour chaque client ? --}}
        <div class="step d-none" id="step3">
            <h2 class="text-center mb-4 fw-bold">{{__('Do you assign an employee to each client?')}}</h2>
            <div class="row text-center w-100">
                {{-- Carte pour "Aucun" --}}
                <div class="col-md-4 mb-4">
                    <div class="card border border-danger shadow-sm h-100 w-100" style="cursor: pointer;">
                        <button class="btn btn-nav w-100 py-4 d-flex flex-column align-items-center" style="border: none;" answer="0">
                            <div class="card-body d-flex flex-column align-items-center">
                                <img src="{{ asset('images/None.png') }}" alt="Aucun" style="width: 100px; height: auto;">
                                <p class="mt-3 fw-bold text-dark">{{__('None')}}</p>
                            </div>
                        </button>
                    </div>
                </div>

                {{-- Carte pour "Un seul" --}}
                <div class="col-md-4 mb-4">
                    <div class="card border border-danger shadow-sm h-100 w-100" style="cursor: pointer;">
                        <button class="btn btn-nav w-100 py-4 d-flex flex-column align-items-center" style="border: none;" answer="1">
                            <div class="card-body d-flex flex-column align-items-center">
                                <img src="{{ asset('images/Barber.png') }}" alt="Un seul" style="width: 100px; height: auto;">
                                <p class="mt-3 fw-bold text-dark">{{__('One')}}</p>
                            </div>
                        </button>
                    </div>
                </div>

                {{-- Carte pour "Plusieurs" --}}
                <div class="col-md-4 mb-4">
                    <div class="card border border-danger shadow-sm h-100 w-100" style="cursor: pointer;">
                        <button class="btn btn-nav w-100 py-4 d-flex flex-column align-items-center" style="border: none;" answer="2">
                            <div class="card-body d-flex flex-column align-items-center">
                                <img src="{{ asset('images/TeamMeeting.png') }}" alt="Plusieurs" style="width: 100px; height: auto;">
                                <p class="mt-3 fw-bold text-dark">{{__('Several')}}</p>
                            </div>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        {{-- Étape 4 : Placez-vous vos clients dans votre enseigne ? --}}
        <div class="step d-none" id="step4">
            <h2 class="text-center mb-4 fw-bold">{{__('Do you place your clients in your brand?')}}</h2>
            <div class="row text-center w-100">
                {{-- Carte pour "Oui" --}}
                <div class="col-md-6 mb-4">
                    <div class="card border border-danger shadow-sm h-100 w-100" style="cursor: pointer;">
                        <a class="btn btn-submit w-100 py-4 d-flex flex-column align-items-center" style="border: none;" answer="0" href="{{ route('entreprise.recap') }}">
                            <div class="card-body d-flex flex-column align-items-center">
                                <img src="{{ asset('images/Validate.png') }}" alt="Oui" style="width: 100px; height: auto;">
                                <p class="mt-3 fw-bold text-dark">{{__('Yes')}}</p>
                            </div>
                        </a>
                    </div>
                </div>

                {{-- Carte pour "Non" --}}
                <div class="col-md-6 mb-4">
                    <div class="card border border-danger shadow-sm h-100 w-100" style="cursor: pointer;">
                        <a class="btn btn-submit w-100 py-4 d-flex flex-column align-items-center" style="border: none;" answer="1" href="{{ route('entreprise.recap') }}">
                            <div class="card-body d-flex flex-column align-items-center">
                                <img src="{{ asset('images/Refuse.png') }}" alt="Non" style="width: 100px; height: auto;">
                                <p class="mt-3 fw-bold text-dark">{{__('No')}}</p>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="{{ asset('js/register.js') }}"></script>
@endsection
