@extends('layouts.app')

@section('title', 'Choix du type de rdv')

@section('content')

    <div class="container d-flex flex-column align-items-center" style="min-height: 100vh;">
        <!-- Navigation section with a back button and progress indicators -->
        <div class="d-flex justify-content-between align-items-center w-100" style="position: relative;">
            <!-- Back button -->
            <a class="btn btn-outline-secondary mt-5 mb-5" id="retour" redirectUrl="{{ route('register.company.register.company') }}" style="position: absolute; left: 0;">
                <i class="bi bi-arrow-left"></i>
            </a>

            <!-- Progress indicators -->
            <div class="d-flex justify-content-center align-items-center w-100 mt-5 mb-5">
                <span class="me-2 bg-primary rounded-circle" style="width: 12px; height: 12px;"></span>
                <span class="mx-2 bg-primary rounded-circle" style="width: 12px; height: 12px;"></span>
                <span class="ms-2 bg-primary rounded-circle" style="width: 12px; height: 12px;"></span>
            </div>
        </div>

        <!-- Progress bar for step navigation -->
        <div class="progress mb-4" style="height: 20px; width: 100%;">
            <div id="progress-bar" class="progress-bar bg-primary" role="progressbar"
                 style="width: 20%;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">
            </div>
        </div>

        <!-- Step 1: Single or multiple clients per time slot -->
        <div class="step" id="step1">
            <h2 class="text-center mb-4 fw-bold">Traitez-vous un ou plusieurs clients lors d’un créneau ?</h2>
            <div class="row text-center w-100">
                <!-- Card for "Single client" option -->
                <div class="col-md-6 mb-4">
                    <div class="card border border-danger shadow-sm h-100 w-100" style="cursor: pointer;">
                        <button class="btn btn-nav w-100 py-4 d-flex flex-column align-items-center" style="border: none;" answer="0">
                            <div class="card-body d-flex flex-column align-items-center">
                                <img src="{{ asset('images/Consulting.png') }}" alt="Un seul client" style="width: 100px; height: auto;">
                                <p class="mt-3 fw-bold text-dark">Un seul (type Avocat)</p>
                            </div>
                        </button>
                    </div>
                </div>

                <!-- Card for "Multiple clients" option -->
                <div class="col-md-6 mb-4">
                    <div class="card border border-danger shadow-sm h-100 w-100" style="cursor: pointer;">
                        <button class="btn btn-nav w-100 py-4 d-flex flex-column align-items-center" style="border: none;" answer="1">
                            <div class="card-body d-flex flex-column align-items-center">
                                <img src="{{ asset('images/Restaurant.png') }}" alt="Plusieurs clients" style="width: 100px; height: auto;">
                                <p class="mt-3 fw-bold text-dark">Plusieurs (type Coiffeur ou Restaurant)</p>
                            </div>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Step 2: Slot selection responsibility -->
        <div class="step d-none" id="step2">
            <h2 class="text-center mb-4 fw-bold">Qui sélectionne le créneau ?</h2>
            <div class="row text-center w-100">
                <!-- Card for "Only you" option -->
                <div class="col-md-6 mb-4">
                    <div class="card border border-danger shadow-sm h-100 w-100" style="cursor: pointer;">
                        <button class="btn btn-nav w-100 py-4 d-flex flex-column align-items-center" style="border: none;" answer="0">
                            <div class="card-body d-flex flex-column align-items-center">
                                <img src="{{ asset('images/Booking.png') }}" alt="Seulement vous" style="width: 100px; height: auto;">
                                <p class="mt-3 fw-bold text-dark">Seulement vous</p>
                            </div>
                        </button>
                    </div>
                </div>

                <!-- Card for "Client and you" option -->
                <div class="col-md-6 mb-4">
                    <div class="card border border-danger shadow-sm h-100 w-100" style="cursor: pointer;">
                        <button class="btn btn-nav w-100 py-4 d-flex flex-column align-items-center" style="border: none;" answer="1">
                            <div class="card-body d-flex flex-column align-items-center">
                                <img src="{{ asset('images/BusinessDecision.png') }}" alt="Le client et vous" style="width: 100px; height: auto;">
                                <p class="mt-3 fw-bold text-dark">Le client et vous</p>
                            </div>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Step 3: Assigning employees to clients -->
        <div class="step d-none" id="step3">
            <h2 class="text-center mb-4 fw-bold">Affectez-vous un salarié pour chaque client ?</h2>
            <div class="row text-center w-100">
                <!-- Card for "None" option -->
                <div class="col-md-4 mb-4">
                    <div class="card border border-danger shadow-sm h-100 w-100" style="cursor: pointer;">
                        <button class="btn btn-nav w-100 py-4 d-flex flex-column align-items-center" style="border: none;" answer="0">
                            <div class="card-body d-flex flex-column align-items-center">
                                <img src="{{ asset('images/None.png') }}" alt="Aucun" style="width: 100px; height: auto;">
                                <p class="mt-3 fw-bold text-dark">Aucun</p>
                            </div>
                        </button>
                    </div>
                </div>

                <!-- Card for "One" option -->
                <div class="col-md-4 mb-4">
                    <div class="card border border-danger shadow-sm h-100 w-100" style="cursor: pointer;">
                        <button class="btn btn-nav w-100 py-4 d-flex flex-column align-items-center" style="border: none;" answer="1">
                            <div class="card-body d-flex flex-column align-items-center">
                                <img src="{{ asset('images/Barber.png') }}" alt="Un seul" style="width: 100px; height: auto;">
                                <p class="mt-3 fw-bold text-dark">Un seul</p>
                            </div>
                        </button>
                    </div>
                </div>

                <!-- Card for "Multiple" option -->
                <div class="col-md-4 mb-4">
                    <div class="card border border-danger shadow-sm h-100 w-100" style="cursor: pointer;">
                        <button class="btn btn-nav w-100 py-4 d-flex flex-column align-items-center" style="border: none;" answer="2">
                            <div class="card-body d-flex flex-column align-items-center">
                                <img src="{{ asset('images/TeamMeeting.png') }}" alt="Plusieurs" style="width: 100px; height: auto;">
                                <p class="mt-3 fw-bold text-dark">Plusieurs</p>
                            </div>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Step 4: Hosting clients at the premises -->
        <div class="step d-none" id="step4">
            <h2 class="text-center mb-4 fw-bold">Placez-vous vos clients dans votre enseigne ?</h2>
            <div class="row text-center w-100">
                <!-- Card for "Yes" option -->
                <div class="col-md-6 mb-4">
                    <div class="card border border-danger shadow-sm h-100 w-100" style="cursor: pointer;">
                        <a class="btn btn-submit w-100 py-4 d-flex flex-column align-items-center" style="border: none;" answer="0" href="{{ route('register.company.register.recap') }}">
                            <div class="card-body d-flex flex-column align-items-center">
                                <img src="{{ asset('images/Validate.png') }}" alt="Oui" style="width: 100px; height: auto;">
                                <p class="mt-3 fw-bold text-dark">Oui</p>
                            </div>
                        </a>
                    </div>
                </div>

                <!-- Card for "No" option -->
                <div class="col-md-6 mb-4">
                    <div class="card border border-danger shadow-sm h-100 w-100" style="cursor: pointer;">
                        <a class="btn btn-submit w-100 py-4 d-flex flex-column align-items-center" style="border: none;" answer="1" href="{{ route('register.company.register.recap') }}">
                            <div class="card-body d-flex flex-column align-items-center">
                                <img src="{{ asset('images/Refuse.png') }}" alt="Non" style="width: 100px; height: auto;">
                                <p class="mt-3 fw-bold text-dark">Non</p>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="{{ asset('js/register.js') }}"></script>
@endsection
