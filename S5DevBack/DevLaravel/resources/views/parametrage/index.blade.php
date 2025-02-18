@extends('base')

@section('title_base', __('Settings2'))
@section('parametrage_active', 'active')

@section('content')
<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
  
    <!-- Pour les notifications -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" />

    <!-- Pour les boutons -->
    <!-- Development -->
    <script src="https://unpkg.com/@popperjs/core@2/dist/umd/popper.min.js"></script>
    <script src="https://unpkg.com/tippy.js@6/dist/tippy-bundle.umd.js"></script>

    <!-- Production -->
    <script src="https://unpkg.com/@popperjs/core@2"></script>
    <script src="https://unpkg.com/tippy.js@6"></script>

</head>
<body>
  
<div class="container">
    <div class="header-profile">
        <h1>{{__("Businesses you work for")}}</h1>
        <br/>
    </div>
    <div class="container-entreprise">
    @foreach (Auth::user()->travailler_entreprises->unique() as $entreprise)
    <div class="entreprise" id="entreprise{{$entreprise->id}}">
        <div style="margin-bottom:10px;">
            <h2>{{ $entreprise->libelle }}</h2>
        </div>
        <p style="margin-bottom:10px;"><strong>{{__('Address')}} : </strong>{{ $entreprise->adresse }}</p>
        {{-- @if (Auth::user()->id == $entreprise->user_id) // Cas créateur
            <p style="color:blue;"><strong>Vous êtes le propriétaire de cette entreprise</strong></p>
        @endif --}}
        <div style="display: inline-flex;  width: 100%;">
        @if (Auth::user()->travailler_entreprises->where('id', $entreprise->id)->first()->pivot->statut == 'Admin')
            <div class="activity-container">
            @foreach ($entreprise->activites as $activite)
            <div class="activity row">
                <div class="activity-description col-lg-8">
                    <strong>{{__('Activity')}} :</strong> {{ $activite->libelle }}
                </div>
                <div class="activity-button col-lg-4">
                    <a class="btn btn-primary light" href="{{ route('parametrage.plage.idEntrepriseAsEmploye', ['entreprise' => $entreprise->id, 'activite' => $activite->id]) }}"><i class="fa fa-eye"></i> {{__('View your time slots')}}</a>
                </div>
            </div>
            @endforeach
            </div>
            <div style="margin-top:10px;">
                <a class="btn btn-primary" href="{{ route('entreprise.services.index', ['entreprise' => $entreprise->id]) }}" style="margin:auto;"><i class="fa fa-wrench"></i> {{__("Slot settings")}}</a>
            </div>
        @elseif (Auth::user()->travailler_entreprises->where('id', $entreprise->id)->first()->pivot->statut == 'Employé')
            <div class="activity-container">
            @foreach ($entreprise->activites as $activite)
            <div class="activity row">
                <div class="activity-description col-lg-8">
                    <strong>{{__('Activity')}} :</strong> {{ $activite->libelle }}
                </div>
                <div class="activity-button col-lg-4">
                    <a class="btn btn-primary light" href="{{ route('parametrage.plage.idEntrepriseAsEmploye', ['entreprise' => $entreprise->id, 'activite' => $activite->id]) }}"><i class="fa fa-eye"></i> {{__('View your time slots')}}</a>
                </div>
            </div>
            @endforeach
            </div>
        @else
        <div style="display: inline-flex;  width: 100%;">
            <p style="margin: auto;"><i>{{__("You got invited in this business")}} :</i></p>
            <a style="margin:auto; margin-right: 1%;" onclick="accepterInvit({{$entreprise->id}},'{{$entreprise->libelle}}')" class="btn btn-primary accept">{{__('Accept invite')}}</a>
            <a style="margin:auto; margin-left:0px;" onclick="refuserInvit({{$entreprise->id}},'{{$entreprise->libelle}}')" class="btn btn-primary reject">{{__('Refuse invite')}}</a>
        </div>
        @endif
    </div>
    </div>
    @endforeach
    </div>
<div>


    <script>
        /* document.addEventListener('DOMContentLoaded', function() { */
            // VARIABLES GLOBALES
        // URL dans le site
        var SITEURL = "{{ url('/parametrage/invit') }}";

// Mise en place du setup du ajax avec le token CSRF
$.ajaxSetup({
    headers: {
    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

function accepterInvit(eId, eLib) {
    $.ajax({
        type: "POST",
        url: SITEURL + "/",
        data: {
            id: {{ Auth::user()->id }},
            type: 'accept',
            idEntreprise: eId,
        },
        success: function (data) {
            displaySuccess('Vous avez accepté l\'invitation.\nVous travaillez maintenant pour ' + eLib);
            // Transformer la possibilité d'accepter en la possibilité de visualiser
            location.reload();
            /* $("#entreprise" + eId + " a").remove();
            $("#entreprise" + eId + " i").remove();
            $("#entreprise" + eId).append('<a class="btn btn-primary light" href="/parametrage/plage/'+eId+'">{{__(`View your time slots`)}}</a>'); {{-- {{ route('parametrage.plage.idEntreprise', ['entreprise' => $entreprise->id]) }} --}} */
        },
        error: function (data) {
            displayError('Erreur lors de l\'acceptation de l\'invitation. Réessayez...');
        }
    });
}

function refuserInvit(eId, eLib) {
    $.ajax({
        type: "POST",
        url: SITEURL + "/",
        data: {
            id: {{ Auth::user()->id }},
            type: 'reject',
            idEntreprise: eId,
        },
        success: function (data) {
            displayMessage('Vous avez refusé l\'invitation de ' + eLib);
            // Retirer l'entreprise de la liste
            $("#entreprise" + eId).remove();
        },
        error: function (data) {
            displayError('Erreur lors du refus de l\'invitation. Réessayez...');
        }
    });
}
        /* }); */
    </script>
   
</body>
@endsection