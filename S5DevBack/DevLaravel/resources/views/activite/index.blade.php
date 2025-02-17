@extends('layouts.app')

@include('base')

@section('title', 'Activité de ' . $entreprise->libelle)

@section('content')
    <div class="container">
        @if($services->isEmpty())
            <h1 style="text-align:center;">Créez votre premier service</h1>
        @endif
        <h2 class="mb-4">Mes Services</h2>

        @php
            // Vérifie si l'utilisateur actuel est administrateur de l'entreprise
            $isAdmin = $entreprise->travailler_users()->wherePivot('idUser', Auth::user()->id)->wherePivot('statut', 'Admin')->first() != null;
        @endphp

        @if($services->isEmpty())
            <p>Aucun service n'a été créé pour {{ $entreprise->libelle }}.</p>
            <a href="{{ route('entreprise.services.create', ['entreprise' => $entreprise->id]) }}" class="btn btn-dark">Créer un service</a>
        @else
            <!-- Tableau listant les services -->
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
                    @php
                        // Vérifie si l'utilisateur travaille sur ce service ou s'il est administrateur
                        $isWorkedByUser = $entreprise->travailler_users()->wherePivot('idUser', Auth::user()->id)->wherePivot('idActivite', $service->id)->first() != null;
                    @endphp
                    @if($isWorkedByUser || $isAdmin)
                        <tr>
                            <td>{{ $service->libelle }}</td>
                            <td>{{ $service->formatted_duree }}</td>
                            <td>
                                @if($isAdmin)
                                    <!-- Actions disponibles pour l'administrateur -->
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
                                    @if($isWorkedByUser)
                                        <a href="{{ route('parametrage.plage.idEntrepriseAsEmploye', ['entreprise' => $entreprise->id, 'activite' => $service->id]) }}" class="btn btn-link">
                                            <i class="fa fa-eye"></i> Voir vos plages
                                        </a>
                                    @endif
                                @else
                                    <!-- Actions disponibles pour un employé non administrateur -->
                                    <a href="{{ route('parametrage.plage.idEntrepriseAsEmploye', ['entreprise' => $entreprise->id, 'activite' => $service->id]) }}" class="btn btn-link">
                                        <i class="fa fa-calendar"></i> Voir les plages
                                    </a>
                                @endif
                            </td>
                        </tr>
                    @endif
                @endforeach
                </tbody>
            </table>
        @endif

        @if(!$services->isEmpty() && $isAdmin)
            <!-- Bouton pour ajouter un nouveau service -->
            <div class="mt-4">
                <a href="{{ route('entreprise.services.create', ['entreprise' => $entreprise->id]) }}" class="btn btn-primary">Ajouter un service</a>
            </div>
        @endif
        <!-- Bouton pour revenir à la page de l'entreprise -->
        <a href="{{ route('entreprise.show', ['entreprise' => $entreprise->id]) }}" class="btn btn-secondary">Retour</a>
    </div>
@endsection
