@extends('base')

@section('title', 'Détail de ' . $entreprise -> libelle)

@section('content')

    <head>
        <meta name="csrf-token" content="{{ csrf_token() }}">
    </head>

    <div class="container">
        <a href="{{ route('entreprise.indexUser') }}">
            <i class="fa fa-arrow-left fa-lg" style="color: #000000;"></i>
        </a>
        <div class="header-profile" style="margin-top: 1rem;"> 
            <h1>Entreprise : {{ $entreprise->libelle }}</h1> 
            <br/>
        </div>
        <div class="entreprise">
            <div class="row res-details-info" style="display: inline-flex; width: 100%;">
                <div class="col-sm-7 col-md-8 col-lg-9" style="display: block; margin:auto; margin-left: 0px;">
                <p><strong>Siren :</strong> {{ $entreprise->siren }}</p>
                <p><strong>Adresse :</strong> {{ $entreprise->adresse }}</p>
                <p><strong>Métier :</strong> {{ $entreprise->metier }}</p>
                <p><strong>Description :</strong> {{ $entreprise->description }}</p>
                <p><strong>Type :</strong> {{ $entreprise->type }}</p>
                <p><strong>Numéro de téléphone :</strong> {{ $entreprise->numTel }}</p>
                <p><strong>Email :</strong> {{ $entreprise->email }}</p>
                </div>
                <div class="col-sm-5 col-md-4 col-lg-3" style="display: block; margin:auto;">
                @if ($entreprise->cheminImg)
                <img class="info-image" src="{{ json_decode($entreprise->cheminImg)[0] }}" alt="{{ $entreprise->libelle }}" height="200vh" width="200vh">
                @else
                <img class="info-image" src="https://www.map24.com/wp-content/uploads/2021/11/6784174_s.jpg" alt="{{ $entreprise->libelle }}" height="200vh" width="200vh">
                @endif
                </div>
            </div>
            @if($entreprise->publier)
                <p><strong>Publié !</strong></p>
                <a class="btn btn-primary light" href="{{ route('entreprise.activites', ['entreprise' => $entreprise->id]) }}"><i class="bi bi-calendar-plus"></i> Réserver une activité</a>
                <a class="btn btn-primary light" href="{{ route('entreprise.services.index', ['entreprise' => $entreprise->id]) }}"><i class="bi bi-tools"></i> Gérer les activités</a>
            @endif
            <h3 style="margin-top: 15px;">Liste des employés</h3>
            <div style="overflow:auto; max-height:400px;">
                @foreach ($entreprise->travailler_users->unique() as $user)
                    @if($user->id == Auth::user()->id)
                    <div class="container-entreprise" id="user{{$user->id}}" style="width:100%;"> 
                        <p><strong>Utilisateur :</strong> {{ $user->nom }} {{ $user->prenom }}</p>
                        <p><strong>Statut :</strong> {{ Auth::user()->travailler_entreprises()->wherePivot('idUser',$user->id)->pluck('statut')[0] }}</p>
                        <p style="margin-bottom: 0%;"><strong><i>Vous</i></strong>
                        @if ($user->id == $entreprise->idCreateur)
                            <strong>(Créateur)</strong></p>
                        @else
                        </p>
                        <a onclick="supprimer({{$user->id}},'{{$user->nom}}','{{$user->prenom}}')" class="btn btn-primary reject">Quitter l'entreprise</a>
                        @endif
                    </div>
                    @else
                        <div class="container-entreprise" id="user{{$user->id}}" {{-- style="display: inline-flex; flex:1" --}}> 
                            <p><strong>Utilisateur :</strong> {{ $user->nom }} {{ $user->prenom }}</p>
                            <p><strong>Statut :</strong> {{ $user->travailler_entreprises()->wherePivot('idUser',$user->id)->pluck('statut')[0] }}</p>
                            @if ($user->id == $entreprise->idCreateur)
                            <p><strong>Créateur</strong></p>
                            @else
                                @if ($user->travailler_entreprises->where('id', $entreprise->id)->first()->pivot->statut == 'Admin')
                                        <a onclick="retrograder({{$user->id}},'{{$user->nom}}','{{$user->prenom}}')" class="btn btn-primary reject">Rétrograder</a>
                                        <a onclick="supprimer({{$user->id}},'{{$user->nom}}','{{$user->prenom}}')" class="btn btn-primary reject">Supprimer</a>
                                    @elseif ($user->travailler_entreprises->where('id', $entreprise->id)->first()->pivot->statut == 'Employé')
                                        <a onclick="promouvoir({{$user->id}},'{{$user->nom}}','{{$user->prenom}}')" class="btn btn-primary accept">Promouvoir</a>
                                        <a onclick="supprimer({{$user->id}},'{{$user->nom}}','{{$user->prenom}}')" class="btn btn-primary reject">Supprimer</a>
                                    @else
                                        <a onclick="supprimer({{$user->id}},'{{$user->nom}}','{{$user->prenom}}')" class="btn btn-primary reject">Annuler l'invitation</a>
                                @endif
                            @endif
                        </div>
                    @endif
                @endforeach
                
        </div>
        
    </div>

    <script>
        // VARIABLES GLOBALES
        // URL dans le site
        var SITEURL = "{{ url('/entreprise/') }}";
        SITEURL = SITEURL + "/"+{{ $entreprise->id }};

        // Mise en place du setup du ajax avec le token CSRF
        $.ajaxSetup({
            headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        function promouvoir(uId, uName, uPrenom){
            $.ajax({
                type: "POST",
                url: SITEURL + "/",
                data: {
                    idEmploye: uId,
                    type: 'upgrade',
                    idEntreprise: {{ $entreprise->id }},
                },
                success: function (data) {
                    // Transformer la possibilité d'accepter en la possibilité de visualiser
                    $("#user" + uId + " a").remove();
                    $("#user" + uId).append('<a onclick="retrograder('+uId+',\''+uName+'\',\''+uPrenom+'\')" class="btn btn-primary reject">Rétrograder</a>');
                    $("#user" + uId).append('<a onclick="supprimer('+uId+',\''+uName+'\',\''+uPrenom+'\')" class="btn btn-primary reject">Supprimer</a>');
                    displaySuccess('Vous avez promu ' +  uName +' ' + uPrenom + '. Il est maintenant administrateur.');
                },
                error: function (data) {
                    displayError('Erreur lors de la promotion. Réessayez...');
                }
            });
        }

        function retrograder(uId, uName, uPrenom){
            $.ajax({
                type: "POST",
                url: SITEURL + "/",
                data: {
                    idEmploye: uId,
                    type: 'downgrade',
                    idEntreprise: {{ $entreprise->id }},
                },
                success: function (data) {
                    // Transformer la possibilité d'accepter en la possibilité de visualiser
                    $("#user" + uId + " a").remove();
                    $("#user" + uId).append('<a onclick="promouvoir('+uId+',\''+uName+'\',\''+uPrenom+'\')" class="btn btn-primary accept">Promouvoir</a>');
                    $("#user" + uId).append('<a onclick="supprimer('+uId+',\''+uName+'\',\''+uPrenom+'\')" class="btn btn-primary reject">Supprimer</a>');
                    displayMessage('Vous avez rétrogrdé ' +  uName +' ' + uPrenom + '. Il est maintenant employé.');
                },
                error: function (data) {
                    displayError('Erreur lors du rétrogradage. Réessayez...');
                }
            });
        }

        function supprimer(uId, uName, uPrenom){
            $.ajax({
                type: "POST",
                url: SITEURL + "/",
                data: {
                    idEmploye: uId,
                    type: 'delete',
                    idEntreprise: {{ $entreprise->id }},
                },
                success: function (data) {
                    // Transformer la possibilité d'accepter en la possibilité de visualiser
                    $("#user" + uId).remove();
                    displayMessage('Vous avez supprimé ' +  uName +' ' + uPrenom + '. Il ne peut plus accéder à votre entreprise.');
                },
                error: function (data) {
                    displayError('Erreur lors de la suppression. Réessayez...');
                }
            });
        }
    </script>

@endsection
