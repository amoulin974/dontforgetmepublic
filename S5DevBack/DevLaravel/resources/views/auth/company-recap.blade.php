@extends('layouts.app')

@section('title', 'Récapitulatif de création de compte')

@section('content')
<!-- <div class="container my-5" style="border: 2px solid blue; padding: 20px; border-radius: 10px;"> -->
<div class="container my-5">
    <div class="d-flex justify-content-center mb-5">
        <span class="me-2 bg-primary rounded-circle" style="width: 10px; height: 10px;"></span>
        <span class="mx-2 bg-primary rounded-circle" style="width: 10px; height: 10px;"></span>
        <span class="ms-2 bg-primary rounded-circle" style="width: 10px; height: 10px;"></span>
    </div>

    <!-- Titre principal -->
    <div class="text-center">
        <h3 style="font-weight: bold;">{{__('Summary')}}</h3>
    </div>

    <!-- Informations sur l'entreprise -->
    <div class="mt-4">
        <h4>{{__('Business info')}}</h4>
        <ul>
            <li><strong>{{__('Business name')}} :</strong> {{ session('company.nomEntreprise') }}</li>
            <li><strong>{{__('SIREN number')}} :</strong> {{ session('company.siren') }}</li>
            <li><strong>{{__('Phone number')}} :</strong> {{ session('company.numTel') }}</li>
            <li><strong>{{__('Email Address')}} :</strong> {{ session('company.email') }}</li>
            <li><strong>{{__('Address')}} :</strong> {{ session('company.rue') }}, {{ session('company.codePostal') }} {{ session('company.ville') }}</li>
        </ul>
    </div>

    <!-- Informations sur le type de rendez-vous -->
    <div class="mt-4">
        <h4>{{__('Appointment type')}}</h4>
        <ul>
            @php
                $keys = array_keys($appointment); // Récupère les clés du tableau
            @endphp

            {{-- Première réponse --}}
            @if($appointment[$keys[0]] == 0)
                <li>{{__("You're treating one client per slot.")}}</li>
            @elseif($appointment[$keys[0]] == 1)
                <li>{{__("You're treating several client per slot.")}}</li>
            @endif

            {{-- Deuxième réponse --}}
            @if($appointment[$keys[1]] == 0)
                <li>{{__('Only you can choose a schedule when you book.')}}</li>
            @elseif($appointment[$keys[1]] == 1)
                <li>{{__('You and your client can choose a schedule when you book.')}}</li>
            @endif

            {{-- Troisième réponse --}}
            @if($appointment[$keys[2]] == 0)
                <li>{{__("You won't assign any employee to each client.")}}</li>
            @elseif($appointment[$keys[2]] == 1)
                <li>{{__('You will assign one employee to each client.')}}</li>
            @elseif($appointment[$keys[2]] == 2)
                <li>{{__('You will (be able to) assign several employee to each client.')}}</li>
            @endif

            {{-- Quatrième réponse --}}
            @if($appointment[$keys[3]] == 0)
                <li>{{__('You will place your clients in your brand.')}}</li>
            @elseif($appointment[$keys[3]] == 1)
                <li>{{__("You won't place your clients in your brand.")}}</li>
            @endif
        </ul>
    </div>

    <!-- Boutons d'action -->
    <div class="d-flex justify-content-between mt-4">
        <!-- Bouton Valider -->
        <form method="POST" action="{{ route('register.company.register.submit') }}">
            @csrf
            <button type="submit" class="btn btn-primary btn-lg">{{__('Validate')}}</button>
        </form>

        <!-- Bouton Modifier -->
        <button class="btn btn-outline-secondary" onclick="window.history.back()">{{__('Edit2')}}</button>
    </div>
</div>
@endsection
