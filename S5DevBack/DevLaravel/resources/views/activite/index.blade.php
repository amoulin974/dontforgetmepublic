@extends('layouts.app')

@include('base')

@section('content')
<div class="container">
    @if($services->isEmpty())
        <h1 style="text-align:center;">Créez votre premier service</h1>
    @endif
    <h2 class="mb-4">Mes Services</h2>

    @if($services->isEmpty())
        <p>Aucun service n'a été créé pour {{ $entreprise->libelle }}.</p>
        <a href="{{ route('entreprise.services.create', ['entreprise' => $entreprise->id]) }}" class="btn btn-dark">Créer un service</a>
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
                    <td>{{ $service->formatted_duree }}</td>
                    <td>
                        <a href="{{ route('entreprise.services.edit', ['entreprise' => $entreprise->id, 'id' => $service->id]) }}" class="btn btn-link">
                            <i class="fa fa-pencil-alt"></i> Modifier
                        </a>
                        <form action="{{ route('entreprise.services.destroy', ['entreprise' => $entreprise->id, 'id' => $service->id]) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-link text-danger">
                                <i class="fa fa-trash"></i> Supprimer
                            </button>
                        </form>
                        <a href="{{ route('entreprise.services.createPlage', ['entreprise' => $entreprise->id, 'id' => $service->id]) }}" class="btn btn-link">
                            <i class="fa fa-calendar"></i> Gérer les plages
                        </a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    @if(!$services->isEmpty())
        <div class="mt-4">
            <a href="{{ route('entreprise.services.create', ['entreprise' => $entreprise->id]) }}" class="btn btn-primary">Ajouter un service</a>
        </div>
    @endif
    <a href="{{ route('entreprise.show', ['entreprise' => $entreprise->id]) }}" class="btn btn-secondary">Retour</a>
</div>
@endsection
