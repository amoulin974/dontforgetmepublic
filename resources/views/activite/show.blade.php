@extends('layouts.app')

@include('base')

@section('title', __('Activities proposed by ') . $entreprise->libelle)

@section('content')
<head>
    <link href='https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css' rel='stylesheet' />
    <script src='https://code.jquery.com/ui/1.12.1/jquery-ui.min.js'></script>
</head>
@php
$user = Auth::user();
    $typeRdvArray = json_decode($entreprise->typeRdv, true);
@endphp
<div class="container">
    <h2 class="mb-4">{{__('Available services')}}</h2>

    @if($services->isEmpty())
        <p>{{__('No service was created for')}} {{ $entreprise->libelle }}.</p>
    @else
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>{{__('Name')}}</th>
                    <th>{{__('Duration')}}</th>
                    <th>Nombre de places</th>
                    <th>{{__('Actions')}}</th>
                </tr>
                </thead>
                <tbody>
                @foreach($services as $service)
                <tr>
                    <td>{{ $service->libelle }}</td>
                    <td>{{ $service->duree }}</td>
                    <td>{{ $service->nbrPlaces }}</td>
                    <td>
                        @if($typeRdvArray[1] == "0" && (!$entreprise->travailler_users->where('pivot.idActivite', $service->id)->contains('id', $user->id))) {
                        <button type="button" id="show-contact" class="btn btn-primary">Contacter l'entreprise</button>
                        @else
                        <a href="{{ route('reservation.create', ['entreprise' => $entreprise->id, 'activite' => $service->id]) }}" class="btn btn-primary">{{ __('Book') }}</a>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @endif
    <a href="{{ route('reserver.index') }}" class="btn btn-secondary">{{__('Back')}}</a>
</div>
<!-- Popup Dialog -->
<div id="dialog-show" title="Informations pour contacter" style="display:none;">
    <p>Contact de l'entreprise {{ $entreprise->libelle }} :</p>
    <ul>
        <li><a href="mailto:{{$entreprise->email}}" style="text-decoration: none;">Email : <i>{{ $entreprise->email }}</i></a></li>
        <li><a href="tel:{{$entreprise->telephone}}" style="text-decoration: none;"> Téléphone : <i>{{ $entreprise->numTel }}</i></a></li>
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
