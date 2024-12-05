@extends('base')

@section('title', $reservation -> id)

@section('content')

    <div class="res-details">
        <div class="res-details-header"> 
            <h1>{{ $reservation->id }}</h1> 
        </div>
        <div class="res-details-content">
            <div class="res-details-info">
                <p><strong>dateRdv:</strong> {{ $reservation->dateRdv }}</p>
                <p><strong>heureDeb:</strong> {{ $reservation->heureDeb }}</p>
                <p><strong>heureFin:</strong> {{ $reservation->heureFin }}</p>
                <p><strong>nbPersonnes:</strong> {{ $reservation->nbPersonnes }}</p>
            </div>
        </div>
    </div>
    
@endsection
