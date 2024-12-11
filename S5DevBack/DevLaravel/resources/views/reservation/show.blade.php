@extends('base')

@section('title', 'Détail résa b°' . $reservation -> id)

@section('content')

    <div class="res-details">
    <p><a href="{{ route('reservation.show', ['reservation' => $reservation->id-1]) }}"><strong><</strong></a>
     | 
    <a href="{{ route('reservation.show', ['reservation' => $reservation->id+1]) }}"><strong>></p></strong></a>
        <div class="res-details-header"> 
            <h1>Id réservation : {{ $reservation->id }}</h1>
            @auth
                @if(Auth::user()->id)               
                    <a class="primary-button-link" href="{{ route('reservation.edit', $reservation->id) }}" >
                        <span>Modifier la réservation</span>
                        <i class="fa fa-edit"></i>
                    </a>
                    <a class="primary-button-link trash" href="{{ route('reservation.delete', $reservation->id) }}" >
                        <span>Supprimer la réservation</span>
                        <i class="fas fa-trash-alt"></i>
                    </a>      
                    @endif                                         
            @endauth
        </div>
        <div class="res-details-content">
            <div class="res-details-info">
                <p><strong>dateRdv:</strong> {{ $reservation->dateRdv }}</p>
                <p><strong>heureDeb:</strong> {{ $reservation->heureDeb }}</p>
                <p><strong>heureFin:</strong> {{ $reservation->heureFin }}</p>
                <p><strong>nbPersonnes:</strong> {{ $reservation->nbPersonnes }}</p>
            </div>
            @if (count($reservation->notifs) >= 1)
            <ul>
            @foreach ($reservation->notifs as $notif)
            <li>
                <br/>
                <p>Id notif : <strong>{{ $notif->id }}</strong></p><br/>
                <div class="info">
                    <p><i>Catégorie :</i> {{ $notif->categorie }}</p>
                    <p><i>Délai :</i> {{ $notif->delai }}</p>
                    <p><i>État :</i> {{ $notif->etat }}</p>
                    <p><i>Contenu :</i> {{ $notif->contenu }}</p>
                </div>
            </li>   
            @endforeach
            </ul>
            @endif
        </div>
        
    </div>

@endsection
