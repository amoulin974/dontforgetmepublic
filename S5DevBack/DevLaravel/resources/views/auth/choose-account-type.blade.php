@extends('layouts.app')

@section('content')
<p>Choix du type de compte créé</p>

<a class="btn btn-link" href="{{ route('register.user.register') }}">
    Créer compte Utilisateur 
</a>

<a class="btn btn-link" href="{{ route('register.company.register.user') }}">
    Créer compte Entreprise 
</a>
@endsection
