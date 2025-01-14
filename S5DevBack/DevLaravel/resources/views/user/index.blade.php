@extends('base')

@section('title', Auth::user()->nom . ' ' . Auth::user()->prenom)
@section('profile_active', 'active')

@section('content')

<div class="container">
    <div style="border-bottom: 2px #1dacff solid;">
        <h1 >Votre profil</h1>
        <br/>
    </div>
    <div class="containerEntreprise">
    <div class="entreprise" id="profil">
        <h2>{{ $utilisateur->nom }} {{ $utilisateur->prenom }}</h2>
        <p><strong>Email : </strong>{{ $utilisateur->email }}</p>
        <p><strong>Numéro de téléphone : </strong>{{ $utilisateur->numTel }}</p>
        <p><strong>Notification par défaut : </strong>{{ $utilisateur->typeNotif }}</p>
        <p><strong>Delai avant notification par défaut : </strong>{{ $utilisateur->delaiAvantNotif }}</p>
        @if ($utilisateur->superadmin)
            <h4><strong>Superadmin</strong></h4>
        @endif
        <a class="btn btn-primary" href="{{ route('register.company.register.company') }}" style="margin:auto;"><i class="fa fa-plus"></i> Créer une entreprise</a>
    </div>
    </div>
<div>

@endsection