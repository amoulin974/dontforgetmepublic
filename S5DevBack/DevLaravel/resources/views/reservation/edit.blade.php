@extends('base')

@section('title', "Modifier la réservation : $reservation->id")

@section('type_request', 'POST')

@section('content')
    @include('reservation.form')
@endsection
