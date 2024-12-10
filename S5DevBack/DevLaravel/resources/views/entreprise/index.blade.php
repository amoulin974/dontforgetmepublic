@extends('base')

@section('title', 'Display entreprise')
@section('entreprises_active', 'active')

@section('content')

    <div class="res-container">
        @foreach ($entreprises as $entreprise)
            <div class="res">
                <h2>{{ $entreprise->libelle }}</h2>
                <div class="info">
                    <p><strong>Siren :</strong> {{ $entreprise->siren }}</p>
                    <p><strong>Adresse :</strong> {{ $entreprise->adresse }}</p>
                    <p><strong>Métier :</strong> {{ $entreprise->metier }}</p>
                    <p><strong>Description :</strong> {{ $entreprise->description }}</p>
                    <p><strong>Type :</strong> {{ $entreprise->type }}</p>
                    <p><strong>Numéro de téléphone :</strong> {{ $entreprise->numtel }}</p>
                    <p><strong>email :</strong> {{ $entreprise->email }}</p>
                    <img src="{{ $entreprise->cheminImg }}" alt="{{ $entreprise->libelle }}" height="300vh" width="300vh">
                    @if($entreprise->publier)
                    <p><strong>Publié !</strong></p>
                    @endif
                </div>
                <a class="secondary-button" href="{{ route('entreprise.show', ['entreprise' => $entreprise->id]) }}">Voir plus</a>
            </div>
        @endforeach
    </div>

    {{ $entreprises -> links() }}
    
@endsection
