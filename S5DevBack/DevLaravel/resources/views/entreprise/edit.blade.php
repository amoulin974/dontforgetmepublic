@extends('base')

@section('title_base', "Modifier " . $entreprise->libelle)

@section('content')
    @include('reservation.form') {{-- Lien à modifier --}}
@endsection