@extends('layouts.app')

@include('base')

@section('title', 'Activités proposés par ' . $entreprise->libelle)

@section('content')
    <div class="container">
        <h2 class="mb-4">Services proposés</h2>

        @if($services->isEmpty())
            <p>Aucun service n'a été créé pour {{ $entreprise->libelle }}.</p>
        @else
            <!-- Tableau listant les services disponibles -->
            <table class="table table-striped">
                <thead>
                <tr>
                    <th>Nom</th>
                    <th>Durée</th>
                    <th>Actions</th>
                </tr>
                </thead>
                <tbody>
                @foreach($services as $service)
                    <tr>
                        <td>{{ $service->libelle }}</td>
                        <td>{{ $service->duree }}</td>
                        <td>
                            <!-- Lien pour réserver un service -->
                            <a href="{{ route('reservation.create', ['entreprise' => $entreprise->id, 'activite' => $service->id]) }}" class="btn btn-primary">Réserver</a>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        @endif
        <!-- Bouton de retour à la liste principale -->
        <a href="{{ route('reserver.index') }}" class="btn btn-secondary">Retour</a>
    </div>
@endsection
