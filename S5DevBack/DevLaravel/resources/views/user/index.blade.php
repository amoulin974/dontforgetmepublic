@extends('base')

@section('title_base', Auth::user()->nom . ' ' . Auth::user()->prenom)
@section('profile_active', 'active')

@section('content')

<div class="container">
    <div class="header-profile">
        <h1 >Votre profil</h1>
        <br/>
    </div>
    <div class="container-entreprise">
    <div class="entreprise" id="profil">
        <h2>{{ $utilisateur->nom }} {{ $utilisateur->prenom }}</h2>
        <p><strong>Email : </strong>{{ $utilisateur->email }}</p>
        <p><strong>Numéro de téléphone : </strong>{{ $utilisateur->numTel }}</p>
        <p><strong>Notification par défaut : </strong>{{ $utilisateur->typeNotif }}</p>
        <p><strong>Delai avant notification par défaut : </strong>{{ $utilisateur->delaiAvantNotif }}</p>
        @if ($utilisateur->superadmin)
            <h4><strong>Superadmin</strong></h4>
        @endif
    </div>
    <a class="btn btn-primary" href="{{ route('entreprise.create') }}" style="margin:auto;"><i class="fa fa-plus"></i> Créer une entreprise</a>
    @if($entreprises->count() > 0)
    <h3 style="margin-top: 2%;">Vos entreprises :</h3>
    <div style="overflow: auto; width:100%; max-height: 50%;">
        @foreach ($entreprises as $entreprise)
        <div class="entreprise">
            <h4>{{ $entreprise->libelle }}</h4>
            <div style="display: inline-flex; width: 100%;">
            <div style="display: block">
            <p><strong>Adresse : </strong>{{ $entreprise->adresse }}</p>
            <p style="margin-bottom: 0%;"><strong>Description :</strong> {{ $entreprise->description }}</p>
            </div>
                <a class="btn btn-primary" href="{{ route('entreprise.show', $entreprise->id) }}" style="margin:auto; margin-right:5%;"><i class="fa fa-eye"></i> Voir plus</a>
            </div>
        </div>
        @endforeach
    </div>
    @endif
    </div>
<div>

@endsection