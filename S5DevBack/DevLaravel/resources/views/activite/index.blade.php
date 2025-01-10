@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="mb-4">Mes Services</h2>

    @if($services->isEmpty())
        <p>Aucun service n'a été créé pour cette entreprise.</p>
        <a href="{{ route('services.create') }}" class="btn btn-dark">Créer un service</a>
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
                        <a href="{{ route('services.edit', $service->id) }}" class="btn btn-link">
                            <i class="fa fa-pencil-alt"></i> Modifier
                        </a>
                        <form action="{{ route('services.destroy', $service->id) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-link text-danger">
                                <i class="fa fa-trash"></i> Supprimer
                            </button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    <div class="mt-4">
        <a href="{{ route('services.create') }}" class="btn btn-dark">Ajouter un service</a>
    </div>
</div>
@endsection
