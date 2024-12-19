@extends('layouts.app')

@section('content')
<p>Création du compte Utilisateur</p>

@if (Route::currentRouteName() === 'register.company.register.user')
    <a class="btn btn-link" href="{{ route('register.company.register.company') }}">
        Créer compte Entreprise 
    </a>
@endif
@endsection
