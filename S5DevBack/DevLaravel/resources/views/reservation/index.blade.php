@extends('base')

@section('title', 'Display resa')
@section('catalogue_active', 'active')

@section('content')
@if (session('success'))
    <div class="success-message" role="alert">
        {{ session('success') }}
    </div>
@endif
<div class="res-container"><div class="res"><a href="{{ route('entreprise.indexUser') }}" class="@yield('add_res_active')"><h2>Ajouter une réservation</h2></a></div></div>

    <div class="res-container">
        @foreach ($reservations as $reservation)
            <div class="res">
                <div class="res-header" >
                    

                    @auth
                        @if(Auth::user()->id)
                            <h2 style="max-height: 8px;">{{ $reservation->id }}</h2>
                            <a class="primary-button-link" href="{{ route('reservation.edit', $reservation->id) }}" >
                                <i class="fa fa-edit"></i>
                            </a>           
                            <a class="primary-button-link trash" href="{{ route('reservation.delete', $reservation->id) }}" >
                                <i class="fas fa-trash-alt"></i>
                            </a>   
                        @else 
                        <h2>{{ $reservation->id }}</h2>                                     
                        @endif
                    @else 
                    <h2>{{ $reservation->id }}</h2>
                    @endauth
                </div>
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
