@extends('base')

@section('title', 'Mes entreprises')
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
                    <p><strong>Numéro de téléphone :</strong> {{ $entreprise->numTel }}</p>
                    <p><strong>email :</strong> {{ $entreprise->email }}</p>
                    @if ($entreprise->cheminImg)
                    <img src="{{ json_decode($entreprise->cheminImg)[0] }}" alt="{{ $entreprise->libelle }}" height="300vh" width="300vh">
                    @else
                    <img src="https://www.map24.com/wp-content/uploads/2021/11/6784174_s.jpg" alt="{{ $entreprise->libelle }}" height="300vh" width="300vh">
                    @endif
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
