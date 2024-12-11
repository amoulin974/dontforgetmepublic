@extends('base')

@section('title', 'Display resa')
@section('catalogue_active', 'active')

@section('content')

    <div class="res-container">
        @foreach ($reservations as $reservation)
            <div class="res">
                <h2>{{ $reservation->id }}</h2>
                <div class="info">
                    <p><strong>dateRdv:</strong> {{ $reservation->dateRdv }}</p>
                    <p><strong>heureDeb:</strong> {{ $reservation->heureDeb }}</p>
                    <p><strong>heureFin:</strong> {{ $reservation->heureFin }}</p>
                    <p><strong>nbPersonnes:</strong> {{ $reservation->nbPersonnes }}</p>
                </div>
                <a class="secondary-button" href="{{ route('reservation.show', ['reservation' => $reservation->id]) }}">Voir plus</a>
            </div>
        @endforeach
    </div>

    {{ $reservations -> links() }}
    
@endsection
