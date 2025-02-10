@extends('layouts.app')

@section('title', 'Récapitulatif de la création d\'entreprise')

@section('content')
<div class="container my-5">
    <div class="d-flex justify-content-center mb-5">
        <span class="me-2 bg-primary rounded-circle" style="width: 12px; height: 12px;"></span>
        <span class="mx-2 bg-primary rounded-circle" style="width: 12px; height: 12px;"></span>
    </div>

    {{-- Titre principal --}}
    <div class="text-center">
        <h3 style="font-weight: bold;">Récapitulatif</h3>
    </div>

    {{-- Informations sur l'entreprise --}}
    <div class="mt-4">
        <h4>Informations sur l'entreprise</h4>
        <ul>
            <li><strong>Nom de l'entreprise :</strong> {{ session('company.nomEntreprise') }}</li>
            <li><strong>Numéro de SIREN :</strong> {{ session('company.siren') }}</li>
            <li><strong>Métier :</strong> {{ session('company.metier') }}</li>
            <li><strong>Numéro de téléphone :</strong> {{ session('company.numTel') }}</li>
            <li><strong>Adresse email :</strong> {{ session('company.email') }}</li>
            <li><strong>Adresse :</strong> {{ session('company.rue') }}, {{ session('company.codePostal') }} {{ session('company.ville') }}</li>
            <li><strong>Description :</strong> {{ session('company.description') }}</li>
        </ul>
    </div>

    {{-- Informations sur le type de rendez-vous --}}
    <div class="mt-4">
        <h4>Type de rendez-vous</h4>
        <ul>
            @php
                $keys = array_keys($appointment); // Récupère les clés du tableau
            @endphp

            {{-- Première réponse --}}
            @if($appointment[$keys[0]] == 0)
                <li>Vous traitez un client par créneau.</li>
            @elseif($appointment[$keys[0]] == 1)
                <li>Vous traitez plusieurs clients (maximum {{ session('capacity') }} personnes) par créneau.</li>
            @endif

            {{-- Deuxième réponse --}}
            @if($appointment[$keys[1]] == 0)
                <li>Seulement vous pouvez sélectionner un créneau pour prendre un rendez-vous.</li>
            @elseif($appointment[$keys[1]] == 1)
                <li>Le client et vous pouvez sélectionner un créneau pour prendre un rendez-vous.</li>
            @endif

            {{-- Troisième réponse --}}
            @if($appointment[$keys[2]] == 0)
                <li>Vous n'affectez aucun salarié à chaque client.</li>
            @elseif($appointment[$keys[2]] == 1)
                <li>Vous affectez un salarié à chaque client</li>
            @elseif($appointment[$keys[2]] == 2)
                <li>Vous affectez (ou pouvez affecter) plusieurs salariés à chaque client.</li>
            @endif

            {{-- Quatrième réponse --}}
            @if($appointment[$keys[3]] == 0)
                <li>Vous placez vos clients dans votre enseigne.</li>
            @elseif($appointment[$keys[3]] == 1)
                <li>Vous ne placez pas vos clients dans votre enseigne.</li>
            @endif
        </ul>
    </div>

    {{-- Boutons d'action --}}
    <div class="d-flex justify-content-between mt-4">
        {{-- Bouton Valider --}}
        <form method="POST" action="{{ route('entreprise.store') }}">
            @csrf
            <button type="submit" class="btn btn-primary btn-lg">Valider</button>
        </form>

        {{-- Bouton Modifier --}}
        <button class="btn btn-outline-secondary" onclick="window.history.back()">Modifier</button>
    </div>
</div>
@endsection
