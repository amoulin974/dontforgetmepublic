@extends('base')

@section('title_base', __('My bookings'))
@section('catalogue_active', 'active')

@section('content')
@if (session('success'))
    <div class="success-message" role="alert">
        {{ session('success') }}
    </div>
@endif

<div class="res-container"><a href="{{ route('reserver.index') }}" class="btn btn-primary"><h2><i class="fa fa-plus-circle"></i> {{__('Add a booking')}}</h2></a></div></div>{{-- Lien à modifier --}}


    @if($reservations == [])
    <div class="res-container">
        <p>{{__("No booking was created yet.")}}</p>
    </div>
    @else
    <div class="res-container">
        <div class="row container">
            @foreach ($reservations as $reservation)
                <div class="col-lg-6 col-xxl-4">
                    <div class="res">
                        <div class="res-header" style="display: inline-flex; width: 100%;">
                            
                            @auth
                                @if(Auth::user()->id)
                                    <h3>{{ $reservation->effectuer_activites()->wherePivot('idUser',Auth::user()->id)->first()->libelle }}</h3>
                                @else 
                                <h3>{{ $reservation->id }}</h3>                                     
                                @endif
                            @else 
                            <h3>{{ $reservation->id }}</h3>
                            @endauth
                        </div>
                        <div class="res-details-info">
                            <p>{{__('Booked at ')}}<strong>{{ $reservation->effectuer_activites()->wherePivot('idUser',Auth::user()->id)->first()->entreprise->libelle }}</strong>
                            {{__(' for the date of ')}}<strong>{{ explode('-',explode(' ',$reservation->dateRdv)[0])[2] }}/{{ explode('-',explode(' ',$reservation->dateRdv)[0])[1] }}/{{ explode('-',explode(' ',$reservation->dateRdv)[0])[0] }}</strong></p>
                            <p>{{__('From')}} <strong>{{ explode(':',$reservation->heureDeb)[0] }}h{{ explode(':',$reservation->heureDeb)[1] }}</strong> {{__('to2')}} <strong>{{ explode(':',$reservation->heureFin)[0] }}h{{ explode(':',$reservation->heureFin)[1] }}</strong></p>
                            @if($reservation->nbPersonnes > 1)
                                <p>{{__(':count people are coming', ['count' => $reservation->nbPersonnes] )}}</p>
                            @else
                                <p>{{__('You are coming ')}}<strong>{{__('alone')}}</strong></p>
                            @endif
                        </div>
                        <div class="text-center">
                            <a class="secondary-button" href="{{ route('reservation.show', ['reservation' => $reservation->id]) }}">{{__('More')}}</a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
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
