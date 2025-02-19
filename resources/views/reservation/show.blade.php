@extends('base')

@section('title_base', 'Détail réservation n°' . $reservation -> id)

@section('content')

@if(Auth::check())
<div  style="display: inline-flex; width: 100%; margin-top:15px;margin-bottom:5px;">
    <a class="btn btn-primary" style="display: block; margin-left:auto; margin-right:1%;" href="{{ route('reservation.edit', $reservation->id) }}" >
        <span>Modifier la réservation</span>
        <i class="fa fa-edit"></i>
    </a>
    {{-- Formulaire pour SUPPRIMER la réservation --}}
    <form action="{{ route('reservation.destroy', $reservation->id) }}" method="POST" style="margin-right:5%;">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn btn-danger"
                onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette réservation ?')">
            Supprimer la réservation
        </button>
    </form>
</div>
@endif
    <div class="container-entreprise" style="display:block; margin:auto; margin-top:10px; width:80%;">
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
                    <p style="margin-bottom: 0;"><i>Catégorie :</i> {{ $notif->categorie }}</p>
                    <p style="margin-bottom: 0;"><i>Délai :</i> {{ explode(':',explode(' ',$notif->delai)[1])[2] }} heures avant</p>
                    <p style="margin-bottom: 0;"><i>État :</i> {{ $notif->etat == 0 ? 'En attente' : 'Envoyé' }}</p>
                    <p><i>{{-- Contenu --}}À envoyer à :</i> {{ $notif->contenu }}</p>
                </div>
            @endforeach
        </div>
            @endif
    </div>

@endsection
