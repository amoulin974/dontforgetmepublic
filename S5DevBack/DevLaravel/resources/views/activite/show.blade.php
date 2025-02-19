@extends('layouts.app')

@include('base')

@section('title', 'Activités proposés par ' . $entreprise->libelle)

@section('content')
    <head>
        <link href='https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css' rel='stylesheet' />
        <script src='https://code.jquery.com/ui/1.12.1/jquery-ui.min.js'></script>
    </head>
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
                    <th>Nombre de places</th>
                    <th>Actions</th>
                </tr>
                </thead>
                <tbody>
                @foreach($services as $service)
                <tr>
                    <td>{{ $service->libelle }}</td>
                    <td>{{ $service->duree }}</td>
                    <td>{{ $service->nbrPlaces }}</td>
                    <td>
                        @if($entreprise->typeRdv[2] == 0)
                        <button type="button" id="show-contact" class="btn btn-primary">Contacter l'entreprise</button>
                        @else
                        <a href="{{ route('reservation.create', ['entreprise' => $entreprise->id, 'activite' => $service->id]) }}" class="btn btn-primary">Réserver</a>
                        @endif
                    </td>
                </tr>
                @endforeach
                </tbody>
            </table>
        @endif
        <!-- Bouton de retour à la liste principale -->
        <a href="{{ route('reserver.index') }}" class="btn btn-secondary">Retour</a>
    </div>

    <!-- Popup Dialog -->
    <div id="dialog-show" title="Informations pour contacter" style="display:none;">
        <ul>Contact de l'entreprise {{ $entreprise->libelle }} :
            <li><a href="mailto:{{$entreprise->email}}" style="text-decoration: none;">Email : <i>{{ $entreprise->email }}</i></a></li>
            <li><a href="tel:{{$entreprise->telephone}}" style="text-decoration: none;"> Téléphone : <i>{{ $entreprise->telephone }}</i></a></li>
        </ul>
    </div>

    <script>
        $(document).ready(function() {
            // Bouton pour ouvrir le popup
            $("#show-contact").click(function() {
                // Popup Dialog
                $("#dialog-show").dialog({
                    modal: true,
                    open: function(event, ui) {
                        $('.ui-widget-overlay').bind('click', function(){
                            $("#dialog-show").dialog('close');
                        });
                    },
                    buttons: {
                        "Fermer": function() {
                            $(this).dialog("close");
                        }
                    }
                });
            });
        });
    </script>
@endsection
