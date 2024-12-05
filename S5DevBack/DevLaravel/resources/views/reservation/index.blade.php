@extends('base')

@section('title', 'Display resa')
@section('catalogue_active', 'active')

@section('content')

    <div class="res-container">
        @foreach ($reservations as $reservation)
            <div class="resa">
                <h2>{{ $reservation->id }}</h2>
                </div>
                <div class="info">
                    <p><strong>dateRdv:</strong> {{ $reservation->dateRdv }}</p>
                    <p><strong>heureDeb:</strong> {{ $reservation->heureDeb }}</p>
                    <p><strong>heureFin:</strong> {{ $reservation->heureFin }}</p>
                    <p><strong>nbPersonnes:</strong> {{ $reservation->nbPersonnes }}</p>
                </div>
            </div>
        @endforeach
    </div>

    {{ $reservations -> links() }}
    
@endsection
