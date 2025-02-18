@extends('base')

@section('title_base', __('Details of booking #') . $reservation -> id)

@section('content')

@if(Auth::check())
<div  style="display: inline-flex; width: 100%; margin-top:15px;margin-bottom:5px;">
    <a class="btn btn-primary" style="display: block; margin-left:auto; margin-right:1%;" href="{{ route('reservation.edit', $reservation->id) }}" >
        <span>{{__('Edit booking')}}</span>
        <i class="fa fa-edit"></i>
    </a>
    {{-- Formulaire pour SUPPRIMER la réservation --}}
    <form action="{{ route('reservation.destroy', $reservation->id) }}" method="POST" style="margin-right:5%;">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn btn-danger"
                onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette réservation ?')">
            {{__('Delete booking')}}
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
                <p>{{__('Booked at ')}}<strong>{{ $reservation->effectuer_activites()->wherePivot('idUser',Auth::user()->id)->first()->entreprise->libelle }}</strong>
                   {{__(' for the date of ')}}<strong>{{ explode('-',explode(' ',$reservation->dateRdv)[0])[2] }}/{{ explode('-',explode(' ',$reservation->dateRdv)[0])[1] }}/{{ explode('-',explode(' ',$reservation->dateRdv)[0])[0] }}</strong>
                   {{__('From')}} <strong>{{ explode(':',$reservation->heureDeb)[0] }}h{{ explode(':',$reservation->heureDeb)[1] }}</strong> {{__('to2')}} <strong>{{ explode(':',$reservation->heureFin)[0] }}h{{ explode(':',$reservation->heureFin)[1] }}</strong>.</p>
                   @if($reservation->nbPersonnes > 1)
                       <p>{{__(':count people are coming', ['count' => $reservation->nbPersonnes] )}}</p>
                   @else
                       <p>{{__('You are coming ')}}<strong>{{__('by yourself')}}</strong></p>
                   @endif
            </div>
        </div>
            @if (count($reservation->notifications) >= 1)
            <p><strong>{{__('Notifications')}} :</strong></p>
            <div style="overflow: auto; max-height:230px;">
            @foreach ($reservation->notifications as $notif)
                <div class="info">
                    <p style="margin-bottom: 0px;"><i>{{__('Category')}} :</i> {{ $notif->categorie }}</p>
                    <p style="margin-bottom: 0px;"><i>{{__('Delay')}} :</i> {{ explode(':',explode(' ',$notif->delai)[1])[2] }} {{__('hours prior')}}</p>
                    <p style="margin-bottom: 0px;"><i>{{__('State')}} :</i> {{ $notif->etat == 0 ? 'En attente' : 'Envoyé' }}</p>
                    <p><i>{{-- Contenu --}}{{__('Send to')}} :</i> {{ $notif->contenu }}</p>
                </div>
            @endforeach
        </div>
            @endif
        </div>

    </div>

@endsection
