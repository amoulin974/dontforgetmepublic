@extends('base')

@section('title', 'Détail résa n°' . $reservation -> id)

@section('content')

@if(Auth::check())
<div  style="display: inline-flex; width: 100%; margin-top:15px;margin-bottom:5px;">
    <a class="btn btn-primary" style="display: block; margin-left:auto; margin-right:5px;" href="{{ route('reservation.edit', $reservation->id) }}" >
        <span>Modifier la réservation</span>
        <i class="fa fa-edit"></i>
    </a>
    <a class="btn btn-primary reject" style="display: block; margin-right:10%;" href="{{ route('reservation.delete', $reservation->id) }}" >
        <span>Supprimer la réservation</span>
        <i class="fas fa-trash-alt"></i>
    </a>
</div>
@endif
    <div class="containerEntreprise" style="display:block; margin:auto; margin-top:10px; width:80%;">
        <div class="res-header">
            @auth
                @if(Auth::user()->id)
                    <h2 style="text-align:center">{{ $reservation->effectuer_activites()->wherePivot('idUser',Auth::user()->id)->first()->libelle }}</h2>
                <br>
                @else 
                <h2>{{ $reservation->id }}</h2>                                     
                @endif
            @else 
            <h2>{{ $reservation->id }}</h2>
            @endauth
        </div>
        <div class="entreprise">
            <div class="res-info" style="text-align: center">
                <p>Réservé chez <strong>{{ $reservation->effectuer_activites()->wherePivot('idUser',Auth::user()->id)->first()->entreprise->libelle }}</strong>
                    pour la date du <strong>{{ explode('-',explode(' ',$reservation->dateRdv)[0])[2] }}/{{ explode('-',explode(' ',$reservation->dateRdv)[0])[1] }}/{{ explode('-',explode(' ',$reservation->dateRdv)[0])[0] }}</strong>
                   à partir de <strong>{{ explode(':',$reservation->heureDeb)[0] }}h{{ explode(':',$reservation->heureDeb)[1] }}</strong> jusqu'à <strong>{{ explode(':',$reservation->heureFin)[0] }}h{{ explode(':',$reservation->heureFin)[1] }}</strong>.</p>
                   @if($reservation->nbPersonnes > 1)
                       <p>Vous y allez à <strong>{{ $reservation->nbPersonnes }}</strong></p>
                   @else
                       <p>Vous y allez <strong>seul(e)</strong></p>
                   @endif
            </div>
        </div>
            @if (count($reservation->notifications) >= 1)
            <p><strong>Notifications :</strong></p>
            <div style="overflow: auto; max-height:230px;">
            @foreach ($reservation->notifications as $notif)
                <div class="info">
                    <p><i>Catégorie :</i> {{ $notif->categorie }}</p>
                    <p><i>Délai :</i> {{ $notif->delai }}</p>
                    <p><i>État :</i> {{ $notif->etat == 0 ? 'En attente' : 'Envoyé' }}</p>
                    <p><i>Contenu :</i> {{ $notif->contenu }}</p>
                </div>
            @endforeach
        </div>
            @endif
        </div>
        
    </div>

@endsection
