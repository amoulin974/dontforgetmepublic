@extends('layouts.app')

@section('title', 'Récapitulatif de la création d\'entreprise')

@section('content')
    <div class="container my-5">
        {{-- Barre de progression avec des cercles indiquant l'étape actuelle --}}
        <div class="d-flex justify-content-center mb-5">
            <span class="me-2 bg-primary rounded-circle" style="width: 12px; height: 12px;"></span>
            <span class="mx-2 bg-primary rounded-circle" style="width: 12px; height: 12px;"></span>
        </div>

        {{-- Titre principal de la page --}}
        <div class="text-center">
            <h3 style="font-weight: bold;">Récapitulatif</h3>
        </div>

        {{-- Informations sur l'entreprise récupérées depuis la session --}}
        <div class="mt-4">
            <h4>Informations sur l'entreprise</h4>
            <ul>
                {{-- Affichage du nom de l'entreprise --}}
                <li><strong>Nom de l'entreprise :</strong> {{ session('company.nomEntreprise') }}</li>
                {{-- Affichage du numéro de SIREN --}}
                <li><strong>Numéro de SIREN :</strong> {{ session('company.siren') }}</li>
                {{-- Affichage du numéro de téléphone --}}
                <li><strong>Numéro de téléphone :</strong> {{ session('company.numTel') }}</li>
                {{-- Affichage de l'adresse email --}}
                <li><strong>Adresse email :</strong> {{ session('company.email') }}</li>
                {{-- Affichage de l'adresse complète (rue, code postal, ville) --}}
                <li><strong>Adresse :</strong> {{ session('company.rue') }}, {{ session('company.codePostal') }} {{ session('company.ville') }}</li>
            </ul>
        </div>

        {{-- Informations sur le type de rendez-vous --}}
        <div class="mt-4">
            <h4>Type de rendez-vous</h4>
            <ul>
                {{-- Récupération des clés du tableau pour simplifier l'affichage --}}
                @php
                    $keys = array_keys($appointment); // Récupère les clés du tableau
                @endphp

                {{-- Première question sur le nombre de clients par créneau --}}
                @if($appointment[$keys[0]] == 0)
                    <li>Vous traitez un client par créneau.</li>
                @elseif($appointment[$keys[0]] == 1)
                    <li>Vous traitez plusieurs clients par créneau.</li>
                @endif

                {{-- Deuxième question sur la possibilité de sélection des créneaux --}}
                @if($appointment[$keys[1]] == 0)
                    <li>Seulement vous pouvez sélectionner un créneau pour prendre un rendez-vous.</li>
                @elseif($appointment[$keys[1]] == 1)
                    <li>Le client et vous pouvez sélectionner un créneau pour prendre un rendez-vous.</li>
                @endif

                {{-- Troisième question sur l'affectation des salariés aux clients --}}
                @if($appointment[$keys[2]] == 0)
                    <li>Vous n'affectez aucun salarié à chaque client.</li>
                @elseif($appointment[$keys[2]] == 1)
                    <li>Vous affectez un salarié à chaque client</li>
                @elseif($appointment[$keys[2]] == 2)
                    <li>Vous affectez (ou pouvez affecter) plusieurs salariés à chaque client.</li>
                @endif

                {{-- Quatrième question sur le placement des clients dans l'enseigne --}}
                @if($appointment[$keys[3]] == 0)
                    <li>Vous placez vos clients dans votre enseigne.</li>
                @elseif($appointment[$keys[3]] == 1)
                    <li>Vous ne placez pas vos clients dans votre enseigne.</li>
                @endif
            </ul>
        </div>

        {{-- Boutons d'action pour valider ou modifier --}}
        <div class="d-flex justify-content-between mt-4">
            {{-- Formulaire pour valider la création de l'entreprise --}}
            <form method="POST" action="{{ route('entreprise.store') }}">
                @csrf
                <button type="submit" class="btn btn-primary btn-lg">Valider</button>
            </form>

            {{-- Bouton pour revenir en arrière et modifier les informations --}}
            <button class="btn btn-outline-secondary" onclick="window.history.back()">Modifier</button>
        </div>
    </div>
@endsection
