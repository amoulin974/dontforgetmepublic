@extends('layouts.app')

@section('title', 'Choix du type de compte à créer')

@section('content')
<div class="container d-flex flex-column align-items-center" style="min-height: 100vh;">
    <!-- Bouton retour -->
    <a href="/" class="btn btn-outline-secondary align-self-start mt-4 mb-4">
        <i class="bi bi-arrow-left"></i>
    </a>

    <!-- Titre principal -->
    <div class="text-center mb-5">
        <h2 class="fw-bold">{{__('Are you a client or do you want to create a business?')}}</h2>
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
                        <p class="mt-3 fw-bold">{{__('I want to make a client account')}}</p>
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
                        <p class="mt-3 fw-bold">{{__('I want to make a business account')}}</p>
                    </div>
                </div>
            </a>
        </div>
    </div>
</div>
@endsection
