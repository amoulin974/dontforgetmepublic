@extends('layouts.app')

@section('content')
<p>Création du compte Entreprise</p>

<a class="btn btn-link" href="{{ route('register.company.register.typeRdv') }}">
    Choix Type RDV
</a>
@endsection