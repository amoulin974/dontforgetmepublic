{{-- @extends('layouts.app')

@section('content')
<!-- <div class="step active" id="step1">
    <h2 class="mb-4">Êtes-vous un client ou souhaitez-vous créer votre entreprise ?</h2>
    <div class="row">
        <div class="col-6">
            <a class="btn btn-outline-primary w-100 py-4" data-next="step2-client" href="{{ route('register.user.register') }}">
                Je souhaite créer mon compte client
            </a>
        </div>
        <div class="col-6">
            <a class="btn btn-outline-primary w-100 py-4" data-next="step2-enterprise" href="{{ route('register.company.register.user') }}">
                Je souhaite créer mon compte entreprise
            </a>
        </div>
    </div>
</div> -->
@endsection --}}

@extends('layouts.app')

@section('content')
<div class="container d-flex flex-column align-items-center" style="min-height: 100vh;">
    <!-- Bouton retour -->
    <a href="/" class="btn btn-outline-secondary align-self-start mb-3">
        <i class="bi bi-arrow-left"></i>
    </a>

    <!-- Titre principal -->
    <div class="text-center mb-5">
        <h2 class="fw-bold">Êtes-vous un client ou souhaitez-vous créer votre entreprise ?</h2>
        <div class="mt-2" style="border-top: 3px solid #FF6F61; width: 80px; margin: 0 auto;"></div>
    </div>

    <!-- Options de choix -->
    <div class="row text-center w-100">
        <!-- Carte pour compte client -->
        <div class="col-md-6 mb-4">
            <a href="{{ route('register.user.register') }}" class="text-decoration-none">
                <div class="card border border-danger shadow-sm h-100" style="cursor: pointer;">
                    <div class="card-body d-flex flex-column align-items-center">
                        <img src="{{ asset('images/FemaleAvatar.png') }}" alt="Icône client" style="width: 100px; height: auto;">
                        <p class="mt-3 fw-bold">Je souhaite créer mon compte client</p>
                    </div>
                </div>
            </a>
        </div>

        <!-- Carte pour compte entreprise -->
        <div class="col-md-6 mb-4">
            <a href="{{ route('register.company.register.user') }}" class="text-decoration-none">
                <div class="card border border-danger shadow-sm h-100" style="cursor: pointer;">
                    <div class="card-body d-flex flex-column align-items-center">
                        <img src="{{ asset('images/Building.png') }}" alt="Icône entreprise" style="width: 100px; height: auto;">
                        <p class="mt-3 fw-bold">Je souhaite créer mon compte entreprise</p>
                    </div>
                </div>
            </a>
        </div>
    </div>

    <!-- Lien vers l'accueil -->
    <div class="mt-4">
        <a href="/" class="text-decoration-none text-dark">
            <i class="bi bi-arrow-left"></i> Retour à l'accueil
        </a>
    </div>
</div>
@endsection
