@extends('base')

@section('title', 'Mes réservations')
@section('catalogue_active', 'active')

@section('content')
@if (session('success'))
    <div class="success-message" role="alert">
        {{ session('success') }}
    </div>
@endif
<div class="res-container"><a href="{{ route('reserver.index') }}" class="btn btn-primary"><h2>Ajouter une réservation</h2></a></div></div>{{-- Lien à modifier --}}

    @if($reservations == [])
    <div class="res-container">
        <p>Aucune réservation n'a été créée.</p>
    </div>
    @else
    <div class="res-container">
        @foreach ($reservations as $reservation)
            <div class="res">
                <div class="res-header" style="display: inline-flex; width: 100%;">
                    
                    @auth
                        @if(Auth::user()->id)
                            <h2>{{ $reservation->effectuer_activites()->wherePivot('idUser',Auth::user()->id)->first()->libelle }}</h2>
                        @else 
                        <h2>{{ $reservation->id }}</h2>                                     
                        @endif
                    @else 
                    <h2>{{ $reservation->id }}</h2>
                    @endauth
                </div>
                <div class="info">
                    <p>Réservé chez <strong>{{ $reservation->effectuer_activites()->wherePivot('idUser',Auth::user()->id)->first()->entreprise->libelle }}</strong>
                     pour la date du <strong>{{ explode('-',explode(' ',$reservation->dateRdv)[0])[2] }}/{{ explode('-',explode(' ',$reservation->dateRdv)[0])[1] }}/{{ explode('-',explode(' ',$reservation->dateRdv)[0])[0] }}</strong></p>
                    <p>À partir de <strong>{{ explode(':',$reservation->heureDeb)[0] }}h{{ explode(':',$reservation->heureDeb)[1] }}</strong> jusqu'à <strong>{{ explode(':',$reservation->heureFin)[0] }}h{{ explode(':',$reservation->heureFin)[1] }}</strong></p>
                    @if($reservation->nbPersonnes > 1)
                        <p>Vous y allez à <strong>{{ $reservation->nbPersonnes }}</strong></p>
                    @else
                        <p>Vous y allez <strong>seul(e)</strong></p>
                    @endif
                </div>
                <a class="secondary-button" href="{{ route('reservation.show', ['reservation' => $reservation->id]) }}">Voir plus</a>
            </div>
        @endforeach
    </div>

    {{ $reservations -> links() }}

    <script>
        $(document).ready(function() {
            setTimeout(() => {
                $('.success-message').fadeOut();
            }, 3000);
        });
    </script>
    @endif
    
@endsection
