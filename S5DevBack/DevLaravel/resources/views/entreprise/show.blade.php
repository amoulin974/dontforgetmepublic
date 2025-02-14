@extends('base')

@section('title_base', 'Détail de ' . $entreprise -> libelle)

@section('content')

    <head>
        <meta name="csrf-token" content="{{ csrf_token() }}">
        {{-- Pour les popups --}}
        <link href='https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css' rel='stylesheet' />
        <script src='https://code.jquery.com/ui/1.12.1/jquery-ui.min.js'></script>
    </head>

    <div class="container">
        @php
         $isAdmin = $entreprise->travailler_users()->wherePivot('idUser',Auth::user()->id)->wherePivot('statut','Admin')->first() != null;
         $isInvite = $entreprise->travailler_users()->wherePivot('idUser',Auth::user()->id)->wherePivot('statut','Invité')->first() != null;
        @endphp
        <a href="{{ route('entreprise.indexUser') }}">
            <i class="fa fa-arrow-left fa-lg" style="color: #000000;"></i>
        </a>
        <div class="header-profile" style="margin-top: 1rem;"> 
            @if ($isAdmin)
            <a href="{{ route('entreprise.edit', ['entreprise' => $entreprise->id]) }}" class="btn btn-primary">{{__('Edit2')}}</a>
            @endif
            <div class="containerEntreprise"> 
                <h1>{{__('Business')}} : {{ $entreprise->libelle }}</h1> 
                <br/>
            </div>
        </div>
        <div class="entreprise">
            <div class="row res-details-info" style="display: inline-flex; width: 100%;">
                <div class="col-sm-7 col-md-8 col-lg-9" style="display: block; margin:auto; margin-left: 0px;">
                <p><strong>Siren :</strong> {{ $entreprise->siren }}</p>
                <p><strong>{{__('Address')}}</strong> {{ $entreprise->adresse }}</p>
                <p><strong>{{__('Job')}} :</strong> {{ $entreprise->metier }}</p>
                <p><strong>{{__('Description')}} :</strong> {{ $entreprise->description }}</p>
                <p><strong>{{__('Type')}} :</strong> {{ $entreprise->type }}</p>
                <p><strong>{{__('Phone number')}} :</strong> {{ $entreprise->numTel }}</p>
                <p><strong>{{__('Email Address')}} :</strong> {{ $entreprise->email }}</p>
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
                <p><strong>{{__('Posted!')}}</strong></p>
                <div style="display: inline-flex; width: 100%;">
                <a class="btn btn-primary light" href="{{ route('entreprise.activites', ['entreprise' => $entreprise->id]) }}" style="display:block; margin-left:1%;margin-right:10px;"><i class="bi bi-calendar-plus"></i> {{ __('Book activity') }}</a>
                @if ($isAdmin)
                <a class="btn btn-primary light" href="{{ route('entreprise.services.index', ['entreprise' => $entreprise->id]) }}" style="display:block; margin-left:0px;margin-right:auto;"><i class="bi bi-tools"></i> {{__('Manage activities')}}</a>
                @elseif(!$isInvite)
                <a class="btn btn-primary light" href="{{ route('entreprise.services.index', ['entreprise' => $entreprise->id]) }}" style="display:block; margin-left:0px;margin-right:auto;"><i class="fa fa-eye"></i> Visualiser vos plages</a>
                @endif
            </div>
            @else
                <p><strong>Non publié (en attente de création de votre première activité)</strong></p>
                <a class="btn btn-primary" href="{{ route('entreprise.services.index', ['entreprise' => $entreprise->id]) }}" style="display:block;margin-left:auto;margin-right:auto;">Créer votre première activité</a>
            @endif
            @if($isInvite)
            <div id="invitation" style="display: inline-flex;  width: 100%;">
                <p style="margin: auto; margin-right: 1%;"><i>Vous êtes invité dans cette entreprise :</i></p>
                <a style="margin:auto; margin-left:1%; margin-right: 1%;" onclick="accepterInvit({{$entreprise->id}},'{{$entreprise->libelle}}')" class="btn btn-primary accept">Accepter l'invitation</a>
                <a style="margin:auto; margin-left:0px;" onclick="refuserInvit({{$entreprise->id}},'{{$entreprise->libelle}}')" class="btn btn-primary reject">Refuser l'invitation</a>
            </div>
            @endif
            <div style="display: inline-flex; width: 100%;margin-top: 15px;">
            <h3>{{__('Employees list')}}</h3>
            @if ($isAdmin)
            <a class="btn btn-primary" id="addEmploye" style="display:block; margin:auto; margin margin-left:auto;margin-right:20%;"><i class="fa fa-user-plus"></i> {{__('Add employee')}}</a>
            @endif
            </div>
            <div style="overflow:auto; max-height:400px;">
                @php
                    /* Récupérer la liste des travailleurs travaillant dans l'entreprise pour pouvoir les réinviter */
                    $userTravaillant = $entreprise->travailler_users->unique();
                @endphp
                @foreach ($entreprise->travailler_users->unique() as $user)
                    @if($user->id == Auth::user()->id)
                    <div class="container-entreprise" id="user{{$user->id}}" style="width:100%;"> 
                        <p><strong>{{__('User')}} :</strong> {{ $user->nom }} {{ $user->prenom }}</p>
                        <p><strong>{{__('Status')}} :</strong> {{ Auth::user()->travailler_entreprises()->wherePivot('idUser',$user->id)->wherePivot('idEntreprise',$entreprise->id)->pluck('statut')[0] }}</p>
                        <div style="display: inline-flex; width: 100%;">
                        <p style="margin:auto; margin-left:0%; margin-bottom: 0%;"><strong><i>{{__('You')}}</i></strong>
                        @if ($user->id == $entreprise->idCreateur)
                            <strong>({{__('Creator')}})</strong></p>
                        @else
                        </p>
                        @if(!$isInvite)
                        <a style="margin:auto; margin-right:5%;" onclick="quitterEntreprise({{$user->id}},'{{$user->nom}}','{{$user->prenom}}')" class="btn btn-primary reject">Quitter l'entreprise</a>
                        @endif
                        @endif
                        </div>
                    </div>
                    @else
                        <div class="container-entreprise" id="user{{$user->id}}" {{-- style="display: inline-flex; flex:1" --}}> 
                            <p><strong>{{__('User')}} :</strong> {{ $user->nom }} {{ $user->prenom }}</p>
                            <p><strong>{{__('Status')}} :</strong> {{ $user->travailler_entreprises()->wherePivot('idUser',$user->id)->wherePivot('idEntreprise',$entreprise->id)->pluck('statut')[0] }}</p>
                            @if ($user->id == $entreprise->idCreateur)
                            <p><strong>{{__('Creator')}}</strong></p>
                            @elseif($isAdmin)
                                @if ($user->travailler_entreprises->where('id', $entreprise->id)->first()->pivot->statut == 'Admin')
                                        <a onclick="retrograder({{$user->id}},'{{$user->nom}}','{{$user->prenom}}')" class="btn btn-primary reject">Rétrograder</a>
                                        <a onclick="supprimer({{$user->id}},'{{$user->nom}}','{{$user->prenom}}')" class="btn btn-primary reject">{{__('Delete')}}</a>
                                    @elseif ($user->travailler_entreprises->where('id', $entreprise->id)->first()->pivot->statut == 'Employé')
                                        <a onclick="promouvoir({{$user->id}},'{{$user->nom}}','{{$user->prenom}}')" class="btn btn-primary accept">{{__('Upgrade')}}</a>
                                        <a onclick="supprimer({{$user->id}},'{{$user->nom}}','{{$user->prenom}}')" class="btn btn-primary reject">{{__('Delete')}}</a>
                                    @else
                                        <a onclick="annulerInvit({{$user->id}},'{{$user->nom}}','{{$user->prenom}}')" class="btn btn-primary reject">Annuler l'invitation</a>
                                @endif
                            @endif
                        </div>
                    @endif
                @endforeach
                
        </div>
        
    </div>


    <!-- Popup -->
    <div id="dialog" title="{{__('Add employee')}}" style="display:none;">
        <form>
            <label for="employe">Saisissez l'email de l'employé(e)</label>
            <input type="email" id="employe" name="employe" required>
            <label for="activites">Sélectionnez les activités de l'employé(e)</label>
            <div style="width: 100%;">
            <button type="button" id="all" onclick="checkAll()" style="display:block; margin:auto; margin-bottom:1%;">Tout sélectionner</button>
            </div>
            <div id="activites" name="activites" style="overflow: auto; display:block; max-height:50%;">
                @foreach ($entreprise->activites as $activite)
                    <label for="{{ $activite->id }}"><input type="checkbox" id="{{ $activite->id }}" value="{{ $activite->id }}"> {{ $activite->libelle }}</label><br>
                @endforeach
            </select>
        </form>
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
                    $("#user" + uId).append('<a onclick="supprimer('+uId+',\''+uName+'\',\''+uPrenom+'\')" class="btn btn-primary reject">{{__("Delete")}}</a>');
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
                    $("#user" + uId).append('<a onclick="promouvoir('+uId+',\''+uName+'\',\''+uPrenom+'\')" class="btn btn-primary accept">{{__("Upgrade")}}</a>');
                    $("#user" + uId).append('<a onclick="supprimer('+uId+',\''+uName+'\',\''+uPrenom+'\')" class="btn btn-primary reject">{{__("Delete")}}</a>');
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

        function quitterEntreprise(uId, uName, uPrenom){
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
                    /* displayMessage('{{__("You")}} (' +  uName +' ' + uPrenom + ') avez quitté l\'entreprise.'); */
                    // Redirection après 2 secondes
                    /* setTimeout(function(){ */
                        localStorage.setItem('success', '{{__("You")}} (' +  uName +' ' + uPrenom + ') avez quitté l\'entreprise.');
                        window.location.href = "{{ route('entreprise.indexUser') }}";
                    /* }, 2000); */
                },
                error: function (data) {
                    displayError('Erreur lors de la suppression. Réessayez...');
                }
            });
        }

        function annulerInvit(uId, uName, uPrenom){
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
                    displayMessage('Vous avez annuler l\'invitation pour ' +  uName +' ' + uPrenom + '. Il ne peut plus rejoindre votre entreprise.');
                },
                error: function (data) {
                    displayError('Erreur lors de l\'annulation. Réessayez...');
                }
            });
        }

        var checked = [];
        var allSelected = false;

        $(document).ready(function() {
            $("input[type='checkbox']").change(function(){
                if(this.checked){
                    checked.push($(this).val());
                    if (allSelected){
                        allSelected = false;
                    }
                }
                else{
                    checked.splice(checked.indexOf($(this).val()), 1);
                }
            });

            $("#addEmploye").click(function(){
                var dialog = $("#dialog");
                dialog.dialog({
                    modal: true,
                    closeOnEscape: true,
                    open: function(event, ui) {
                        $('.ui-widget-overlay').bind('click', function(){
                            $('#dialog').dialog('close');
                            $("#employe").val('');
                            uncheckAll();
                        });
                    },
                    buttons: {
                        "Inviter": function() {
                            var email = $("#employe").val();
                            // Vérifier que l'employé n'est pas déjà dans l'entreprise
                            @foreach ($entreprise->refresh()->travailler_users->unique() as $user)
                                if ("{{ $user->email }}" == email){
                                    displayError('Cet employé est déjà dans votre entreprise.');
                                    $('#dialog').dialog('close');
                                    $("#employe").val('');
                                    uncheckAll();
                                    return;
                                }
                            @endforeach
                            let boolExist = false;
                            @foreach (\App\Models\User::pluck('email') as $mail)
                                if ("{{ $mail }}" == email){
                                    boolExist = true;
                                }
                            @endforeach
                            if(email == ""){
                                displayWarning('Veuillez saisir un email.');
                            }
                            else if(boolExist == false){
                                displayError('Cet email ne correspond à aucun compte.');
                            }
                            if (checked.length == 0){
                                displayWarning('Veuillez sélectionner au moins une activité.');
                            }
                            if (checked.length != 0 && boolExist){
                                $.ajax({
                                    type: "POST",
                                    url: SITEURL + "/",
                                    data: {
                                        email: email,
                                        type: 'invite',
                                        idEntreprise: {{ $entreprise->id }},
                                        activites: checked,
                                    },
                                    success: function (data) {
                                        displaySuccess('Vous avez invité ' + email + ' (' + data.nom + ' ' + data.prenom + ') à rejoindre votre entreprise.');
                                        $(".container-entreprise").last().after('<div class="container-entreprise" id="user'+data.id+'"> <p><strong>{{__("User")}} :</strong> '+data.nom+' '+data.prenom+'</p> <p><strong>{{__("Status")}} :</strong> Invité</p> <a onclick="annulerInvit('+data.id+',\''+data.nom+'\',\''+data.prenom+'\')" class="btn btn-primary reject">Annuler l\'invitation</a> </div>');

                                        $('#dialog').dialog('close');
                                        $("#employe").val('');
                                        uncheckAll();
                                    },
                                    error: function (data) {
                                        displayError('Erreur lors de l\'ajout. Réessayez...');
                                    }
                                });
                            }
                        },
                        "Annuler": function() {
                            $('#dialog').dialog('close');
                            $("#employe").val('');
                            uncheckAll();
                        }
                    }
                });
            });
        });

function checkAll() {
    var checkboxes = document.querySelectorAll('input[type="checkbox"]');
    checked = [];
    checkboxes.forEach((checkbox) => {
        checkbox.checked = true;
        checked.push(checkbox.value);
    });
    checked = checked.splice(1, checked.length);
    allSelected = true;
}

function uncheckAll() {
    var checkboxes = document.querySelectorAll('input[type="checkbox"]');
    checkboxes.forEach((checkbox) => {
        checkbox.checked = false;
        
    });
    checked = [];
    allSelected = false;
}

        // URL dans le site
        var SITEURL2 = "{{ url('/parametrage/invit') }}";

function accepterInvit(eId, eLib) {
    $.ajax({
        type: "POST",
        url: SITEURL2 + "/",
        data: {
            id: {{ Auth::user()->id }},
            type: 'accept',
            idEntreprise: eId,
        },
        success: function (data) {
            displaySuccess('Vous avez accepté l\'invitation.\nVous travaillez maintenant pour ' + eLib);
            // Retirer l'invitation
            $("#invitation").remove();
            // Retirer l'employé correspondant
            $("#user{{ Auth::user()->id }}").remove();
            // Ajouter à la liste des employés
            $(".entreprise").append('<div class="container-entreprise" id="user{{ Auth::user()->id }}"> <p><strong>{{__("User")}} :</strong> {{ Auth::user()->nom }} {{ Auth::user()->prenom }}</p> <p><strong>{{__("Status")}} :</strong> Employé</p> <div style="display: inline-flex; width: 100%;"> <p style="margin:auto; margin-left:0%; margin-bottom: 0%;"><strong><i>{{__("You")}}</i></strong></p> <a style="margin:auto; margin-right:5%;" onclick="quitterEntreprise({{ Auth::user()->id }},\'{{ Auth::user()->nom }}\',\'{{ Auth::user()->prenom }}\')" class="btn btn-primary reject">Quitter l\'entreprise</a> </div> </div>');
       },
        error: function (data) {
            displayError('Erreur lors de l\'acceptation de l\'invitation. Réessayez...');
        }
    });
}

function refuserInvit(eId, eLib) {
    $.ajax({
        type: "POST",
        url: SITEURL2 + "/",
        data: {
            id: {{ Auth::user()->id }},
            type: 'reject',
            idEntreprise: eId,
        },
        success: function (data) {
            //displayMessage('Vous avez refusé l\'invitation de ' + eLib);
            // Retirer les boutons d'acceptation et de refus
            $("#invitation").remove();
            // Redirection après 2 secondes
            /* setTimeout(function(){ */
                // Stocker le message de succès dans le stockage local
                localStorage.setItem('success', 'Vous avez refusé l\'invitation de ' + eLib);
                window.location.href = "{{ route('entreprise.indexUser') }}";
                
            /* }, 2000); */
        },
        error: function (data) {
            displayError('Erreur lors du refus de l\'invitation. Réessayez...');
        }
    });
}
    </script>

@endsection
