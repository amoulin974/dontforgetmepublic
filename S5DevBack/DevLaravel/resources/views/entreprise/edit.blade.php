@extends('base')

@section('title', "Modifier " . $entreprise->libelle)

@section('content')
    @include('reservation.form') {{-- Lien à modifier --}}
@endsection