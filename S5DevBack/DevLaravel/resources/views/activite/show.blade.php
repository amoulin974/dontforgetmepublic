@extends('layouts.app')

@include('base')

@section('title', 'Activité ' . $service->libelle)

@section('content')
<div class="container">
    <h2 class="mb-4">Services proposés</h2>

    @if($services->isEmpty())
        <p>Aucun service n'a été créé pour {{ $entreprise->libelle }}.</p>
    @else
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
                        <a href="{{ route('reservation.create', ['entreprise' => $entreprise->id, 'activite' => $service->id]) }}" class="btn btn-primary">Réserver</a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @endif
    <a href="{{ route('reserver.index') }}" class="btn btn-primary">Retour</a>
</div>
@endsection
