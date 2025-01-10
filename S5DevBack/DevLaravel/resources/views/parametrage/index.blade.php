@extends('base')

@section('title', 'Paramétrage')
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

    <link rel="stylesheet" href="{{ asset('css/base.css') }}">

</head>
<body>
  
<div class="container">
    <div style="border-bottom: 2px blue;">
        <h1 >Les entreprises dans lesquels vous travaillez</h1>
        <br/>
    </div>
    <div class="containerEntreprise">
    @foreach (Auth::user()->travailler_entreprises as $entreprise)
    <div class="entreprise" id="entreprise{{$entreprise->id}}">
        <h2>{{ $entreprise->libelle }}</h2>
        <p><strong>Adresse : </strong>{{ $entreprise->adresse }}</p>
        {{-- @if (Auth::user()->id == $entreprise->user_id) // Cas créateur
            <p style="color:blue;"><strong>Vous êtes le propriétaire de cette entreprise</strong></p>
        @endif --}}
        @if (Auth::user()->travailler_entreprises->where('id', $entreprise->id)->first()->pivot->statut == 'Admin')
            <a class="btn btn-primary" href="{{ route('parametrage.plage.idEntreprise', ['entreprise' => $entreprise->id]) }}">Paramétrer les plages</a>
            <a class="btn btn-primary light" href="{{ route('parametrage.plage.idEntrepriseAsEmploye', ['entreprise' => $entreprise->id]) }}">Visualiser vos plages</a>
        @elseif (Auth::user()->travailler_entreprises->where('id', $entreprise->id)->first()->pivot->statut == 'Employé')
            <a class="btn btn-primary light" href="{{ route('parametrage.plage.idEntrepriseAsEmploye', ['entreprise' => $entreprise->id]) }}">Visualiser vos plages</a>
        @else
            <p ><i>Vous êtes invité dans cette entreprise :</i></p>
            <a onclick="accepterInvit({{$entreprise->id}},'{{$entreprise->libelle}}')" class="btn btn-primary accept">Accepter l'invitation</a>
            <a onclick="refuserInvit({{$entreprise->id}},'{{$entreprise->libelle}}')" class="btn btn-primary reject">Refuser l'invitation</a>
        @endif
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
            $("#entreprise" + eId + " a").remove();
            $("#entreprise" + eId + " i").remove();
            $("#entreprise" + eId).append('<a class="btn btn-primary light" href="/parametrage/plage/'+eId+'">Visualiser vos plages</a>'); {{-- {{ route('parametrage.plage.idEntreprise', ['entreprise' => $entreprise->id]) }} --}}
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