@extends('base')

@section('title', 'Détail de ' . $entreprise -> libelle)

@section('content')

    <div class="res-details">
    <p><a href="{{ route('entreprise.show', ['entreprise' => $entreprise->id-1]) }}"><strong><</strong></a>
     | 
    <a href="{{ route('entreprise.show', ['entreprise' => $entreprise->id+1]) }}"><strong>></p></strong></a>
        <div class="res-details-header"> 
            <h1>Entreprise : {{ $entreprise->libelle }}</h1> 
        </div>
        <div class="res-details-content">
            <div class="res-details-info">
                <p><strong>Siren :</strong> {{ $entreprise->siren }}</p>
                <p><strong>Adresse :</strong> {{ $entreprise->adresse }}</p>
                <p><strong>Métier :</strong> {{ $entreprise->metier }}</p>
                <p><strong>Description :</strong> {{ $entreprise->description }}</p>
                <p><strong>Type :</strong> {{ $entreprise->type }}</p>
                <p><strong>Numéro de téléphone :</strong> {{ $entreprise->numTel }}</p>
                <p><strong>email :</strong> {{ $entreprise->email }}</p>
                @if($entreprise->publier)
                <p><strong>Publié !</strong></p>
                <a href="{{ route('reservation.create', ['entreprise' => $entreprise->id]) }}" class="@yield('add_res_active')">Ajouter une réservation</a>
                @endif
            </div>
            @if (count($entreprise->plages) >= 1)
            <ul>
            @foreach ($entreprise->plages as $plage)
            <li>
                <br/>
                <p>Id plage : <strong>{{ $plage->id }}</strong></p><br/>
                <div class="info">
                    <p><i>heureDeb :</i> {{ $plage->heureDeb }}</p>
                    <p><i>heurFin :</i> {{ $plage->heureFin }}</p>
                    <p><i>intervalle :</i> {{ $plage->intervalle }}</p>
                    <p><i>planTables :</i> {{ $plage->planTables }}</p>
                </div>
            </li>   
            @endforeach
            </ul>
            @endif
        </div>
        
    </div>

@endsection
