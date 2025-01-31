@extends('base')

@section('title_base', 'Paramétrage des plages de l\'entreprise n°' . $entreprise -> id)
@section('parametrage_active', 'active')

@section('content')
<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
  
    <!-- Pour le calendrier -->
    <!-- <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" /> -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.9.0/fullcalendar.css" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.24.0/moment.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.9.0/fullcalendar.js"></script>
    <!-- Import FullCalendar locales CDN -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.10.2/locale-all.js"></script>
    <link rel="stylesheet" href="{{ asset('css/base.css') }}">
  
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

    <!-- Pour les popups -->
    <!-- <script src="https://cdn.jsdelivr.net/npm/@simondmc/popup-js@1.4.3/popup.min.js"></script> -->
    <link href='https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css' rel='stylesheet' />
    <script src='https://code.jquery.com/ui/1.12.1/jquery-ui.min.js'></script>

    <!-- Pour les multimonth -->
    <!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar-multimonth/1.0.0/fullcalendar-multimonth.min.css" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar-multimonth/1.0.0/fullcalendar-multimonth.min.js"></script> -->

</head>
<body>
  
<div class="containerCalendar">
    <h3>Calendrier des plages de {{ $entreprise->libelle }}</h3>
    <h4>Activité : {{ $activite->libelle }} ({{ explode(':',$activite->duree)[0] }}h{{ explode(':',$activite->duree)[1] }})</h4>
    <div id='calendar'></div>
    @php
        $userTravaillantSurAct = App\Models\User::whereIn("id",$entreprise->travailler_users()->wherePivot("idActivite",$activite->id)->pluck("idUser"))->get();
    @endphp

    <!-- Popup Dialog -->
    <div id="dialogTitre" title="Ajout d'une plage" style="display:none;">
        <form>
            <p>Quel employé chosir ?</p>
            <div style="width: 100%;">
                <button type="button" id="all" onclick="checkAll()" style="display:block; margin:auto; margin-bottom:1%;">Tout sélectionner</button>
            </div>
            <div id="employes" name="employes" style="overflow: auto; display:block; max-height:50%;">
                @foreach($userTravaillantSurAct as $employe)
                    @if($employe->id == Auth::user()->id)
                    <label for="{{ Auth::user()->id }}"><input type="checkbox" id="{{ Auth::user()->id }}" value="{{ Auth::user()->id }}"> {{ Auth::user()->nom }} {{ Auth::user()->prenom }} (Vous)</label><br>
                    @else
                    <label for="{{ $employe->id }}"><input type="checkbox" id="{{ $employe->id }}" value="{{ $employe->id }}"> {{ $employe->nom }} {{ $employe->prenom }}</label><br>
                    @endif
                @endforeach
            </div><br>
            <p><strong>Interval entre chaque début d'activité :</strong> {{ $activite->duree }}</p>
        </form>
    </div>

    <!-- Popup Dialog Modif -->
    <div id="dialogModif" title="Ajout d'une plage" style="display:none;">
        <form>
            <p>Quel employé chosir ?</p>
            {{-- <select name="employeModif" id="employeModif" class="text ui-widget-content ui-corner-all">
                <option value="{{ Auth::user()->id }}">{{ Auth::user()->nom }} {{ Auth::user()->prenom }} (Vous)</option> --}}
            <div style="width: 100%;">
                <button type="button" id="all" onclick="checkAllModif()" style="display:block; margin:auto; margin-bottom:1%;">Tout sélectionner</button>
            </div>
                <div id="employesModif" name="employesModif" style="overflow: auto; display:block; max-height:50%;">
                @foreach($userTravaillantSurAct as $employe)
                    {{-- <option value="{{ $employe->id }}">{{ $employe->nom }} {{ $employe->prenom }}</option> --}}
                    @if($employe->id == Auth::user()->id)
                    <label for="{{ Auth::user()->id }}Modif"><input type="checkbox" id="{{ Auth::user()->id }}Modif" value="{{ Auth::user()->id }}"> {{ Auth::user()->nom }} {{ Auth::user()->prenom }} (Vous)</label><br>
                    @else
                    <label for="{{ $employe->id }}Modif"><input type="checkbox" id="{{ $employe->id }}Modif" value="{{ $employe->id }}"> {{ $employe->nom }} {{ $employe->prenom }}</label><br>
                    @endif
                @endforeach
            </div><br>
              {{-- </select> --}}
              <p><strong>Interval entre chaque début d'activité :</strong> {{ $activite->duree }}</p>
        </form>
    </div>

    <!-- Popup Dialog Suppression -->
    <div id="dialog-confirm" title="Voulez-vous vraiment supprimer ?" style="display:none;">
        <p><span class="ui-icon ui-icon-alert" style="float:left;"></span>Cette plage sera définitivement supprimé. Voulez-vous continuer ?</p>
    </div>
</div>

<script>
var checked = [];

function checkAll() {
    var checkboxes = document.querySelectorAll('input[type="checkbox"]');
    checked = [];
    checkboxes.forEach((checkbox) => {
        checkbox.checked = true;
        // Vérifier si l'id fini par Modif avec une regex
        if(checkbox.id.match(/Modif$/)){
            checkbox.checked = false;
        }
        else {
            checked.push(checkbox.value);
        }
    });
    checked = checked.splice(1,checked.length);
}

function checkAllModif() {
    var checkboxes = document.querySelectorAll('input[type="checkbox"]');
    checked = [];
    checkboxes.forEach((checkbox) => {
        checkbox.checked = false;
        // Vérifier si l'id fini par Modif avec une regex
        if(checkbox.id.match(/Modif$/)){
            checkbox.checked = true;
            checked.push(checkbox.value);
        }
    });
    checked = checked.splice(1,checked.length);
}

function uncheckAll() {
    var checkboxes = document.querySelectorAll('input[type="checkbox"]');
    checkboxes.forEach((checkbox) => {
        checkbox.checked = false;
    });
    checked = [];
}
$(document).ready(function () {

    $("input[type='checkbox']").change(function(){
        if(this.checked){
            checked.push($(this).val());
        }
        else{
            checked.splice(checked.indexOf($(this).val()), 1);
        }
    });



// VARIABLES GLOBALES
// URL dans le site
var SITEURL = "{{ url('/entreprise/') }}";
SITEURL = SITEURL + "/" + {{ $entreprise->id }} + "/services/" + {{ $activite->id }} + "/plage";
var couleurPasses = 'red';
var couleurAjd = 'green';
var DUREE = '{{ $activite->duree }}';
var DUREE_EN_MS = moment.duration(DUREE).asMilliseconds();

// Mise en place du setup du ajax avec le token CSRF
$.ajaxSetup({
    headers: {
    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

// Mise en place du calendrier
var calendar = $('#calendar').fullCalendar({
    header: {
        left: 'prev,next today',
        center: 'title',
        right: 'agendaWeek,agendaDay'
    },
    buttonIcons: false, // show the prev/next text
    locale: 'fr',
    editable: true,
    events: function(start, end, timezone, callback) {
        $.ajax({
            url: SITEURL + "/",
            type: 'GET',
            success: function(data) {
                var events = [];
                var start_datetime;
                var end_datetime;
                $(data).each(function() {
                    start_datetime = this.datePlage.split('T')[0] + 'T' + this.heureDeb + '.000000Z';
                    end_datetime = this.datePlage.split('T')[0] + 'T' + this.heureFin + '.000000Z';
                    if (this.heureFin == '00:00:00') {
                        end_datetime = this.datePlage.split('T')[0] + 'T' + '23:59:59' + '.000000Z';
                    }
                    events.push({
                        id: this.id,
                        title: this.id,
                        start: start_datetime,
                        end: end_datetime,
                        interval: this.interval,
                    });
                });
                callback(events);
            },
            error: function() {
                displayError("Erreur lors de la récupération des plages");
            }
        });
    },
    displayEventTime: true, // false -> don't show the time column in list view
    weekNumbers: true,
    eventRender: function(event, element) {
        if (moment(event.end).isBefore(moment())) {
            element.css('background-color', couleurPasses); // Couleur pour les événements passés
            element.css('border-color', couleurPasses);
        } else if (moment(event.start).isSame(moment(), 'day')) {
            element.css('background-color', couleurAjd); // Couleur pour les événements futurs
            element.css('border-color', couleurAjd);
        }
        if (event.interval) { // Si le nombre de personnes est renseigné
            element.find('.fc-title').after("<br/><span class=\"intervEvent\">" + event.interval + "</span>");
        }
        
    },
    //slotDuration: '{{ $activite->duree }}',
    snapDuration: DUREE,
    /* selectConstraint: {
        start: tatat,
        end: '23:59:59'
    }, */
    /* selectOverlap:false, */
    selectable: true,
    nowIndicator: true,
    selectHelper: true,
    select: function (start, end, allDay) {
        // Vérifiez si l'événement est sur la même journée
        if (selectable(start,end,true)) {
            // Vérifiez que l'événement fait au moins la durée d'une activité
            let diffTime = (moment(end).diff(moment(start), 'hours').toString().length == 1 ? "0" + moment(end).diff(moment(start), 'hours') : moment(end).diff(moment(start), 'hours')) + ":" + ((moment(end).diff(moment(start), 'minutes')%60).toString().length == 1 ? "0" + (moment(end).diff(moment(start), 'minutes')%60) : moment(end).diff(moment(start), 'minutes')%60) + ":00";
            if (diffTime >= '{{ $activite->duree }}') {
                // Vérifiez si l'événement dépasse une journée
                if (moment(start).isSame(end, 'day')) {
                    var start = $.fullCalendar.formatDate(start, "YYYY-MM-DD HH:mm:ss");
                    var end = $.fullCalendar.formatDate(end, "YYYY-MM-DD HH:mm:ss");
                    // Afficher la popup avec les inputs
                    $('#dialogTitre').dialog({
                        modal: true,
                        closeOnEscape: true,
                                open: function(event, ui) {
                                    $('.ui-widget-overlay').bind('click', function(){
                                        //$('#interv').val('00:05:00');
                                        $('#employe').val('{{ Auth::user()->id }}');
                                        $('#dialogTitre').dialog('close');
                                        uncheckAll();
                                    });
                                },
                        buttons: {
                            "Ajouter": function() {
                                //var interv = $('#interv').val();
                                if (checked.length == 0){
                                displayWarning('Veuillez sélectionner au moins un employé.');
                                }
                                else {
                                    $.ajax({
                                        url: SITEURL + "/",
                                        data: {
                                            datePlage: start.split(' ')[0],
                                            heureDeb: start.split(' ')[1],
                                            heureFin: end.split(' ')[1],
                                            interval: '{{ $activite->duree }}',
                                            entreprise_id: {{ $entreprise->id }},
                                            employes_affecter: checked,
                                            type: 'add'
                                        },
                                        type: "POST",
                                        success: function (data) {
                                            $('#dialogTitre').dialog('close');
                                            uncheckAll();
                                            displaySuccess("Plage ajoutée avec succès");

                                            // Désélectionner après la sélection
                                            $('#calendar').fullCalendar('unselect');

                                            // Rafraîchir l'affichage du calendrier
                                            $('#calendar').fullCalendar('refetchEvents');
                                        },
                                        error: function() {
                                            displayError("Erreur lors de l'ajout de la plage. Réssayez...");
                                        }
                                    });
                                }
                            },
                            "Annuler": function() {
                                uncheckAll();
                                $(this).dialog("close");
                            }
                        }
                    });
                } else {
                    displayError("Impossible de créer une plage sur plusieurs jours");
                    // Désélectionner après la sélection
                    $('#calendar').fullCalendar('unselect');
                }
            } else {
                displayWarning("Impossible de créer une plage de moins de {{ $activite->duree }} minutes");
                // Désélectionner après la sélection
                $('#calendar').fullCalendar('unselect');
            }
        }
        else{
            // Désélectionner après la sélection
            $('#calendar').fullCalendar('unselect');
        }
    },
    eventDrop: function (event, delta, revertFunc) {
        var originalStart = moment(event.start).subtract(delta);
        var originalEnd = event.end ? moment(event.end).subtract(delta) : originalStart;

        if (originalStart.isBefore(moment())) {
            displayWarning("Impossible de déplacer un événement passé ou en cours");
            $('#calendar').fullCalendar('unselect');
            revertFunc();
        }
        else if(selectable(event.start,event.end,event.id)){
            // Vérifiez si l'événement dépasse une journée
            if (moment(event.start).isSame(event.end, 'day')) {
                var start = $.fullCalendar.formatDate(event.start, "YYYY-MM-DD HH:mm:ss");
                var end = event.end ? $.fullCalendar.formatDate(event.end, "YYYY-MM-DD HH:mm:ss") : start;
                $.ajax({
                    url: SITEURL + '/',
                    data: {
                        datePlage: start.split(' ')[0],
                        heureDeb: start.split(' ')[1],
                        heureFin: end.split(' ')[1],
                        id: event.id,
                        type: 'update'
                    },
                    type: "POST",
                    success: function (response) {
                        displayMessage("Plage modifié avec succès");
                    }
                });
            } else {
                displayError("Les plages ne peuvent pas dépasser plusieurs jours");
                // Désélectionner après la sélection
                $('#calendar').fullCalendar('unselect');
            }
        } else {
            // Désélectionner après la sélection
            $('#calendar').fullCalendar('unselect');
            // Rafraîchir l'affichage du calendrier
            revertFunc();
            //$('#calendar').fullCalendar('refetchEvents');
        }
    },
    eventClick: function (event) {
        var alreadyChecked = [];
        @foreach (App\Models\Plage::all() as $p)
            if(event.id == {{ $p->id }}){
                @foreach($p->employes as $e)
                    alreadyChecked.push({{ $e->id }});
                @endforeach
            }
        @endforeach

        alreadyChecked.forEach(userWorking => {
            document.getElementById(userWorking+"Modif").checked = true;
            checked.push(userWorking);
        });

        // Vérifiez si la date de début est passée
        if (moment().isAfter(event.start) || moment().isAfter(event.end)) {
            displayWarning("Vous ne pouvez pas modifier une plage passée ou en cours");
        } else {
            var eventAct = event;
            $('#dialogModif').dialog({
                modal: true,
                closeOnEscape: true,
                        open: function(event, ui) {
                            //$('#eventTitleModif').val(eventAct.title ? eventAct.title : 'Titre de l\'plage');
                            //$('#intervModif').val(eventAct.interval ? eventAct.interval : 1);
                            $('.ui-widget-overlay').bind('click', function(){
                                $('#dialogModif').dialog('close');
                                uncheckAll();
                            });
                        },
                buttons: {
                    "Modifier": function() {
                        if (checked.length == 0){
                        displayWarning('Veuillez sélectionner au moins un employé.');
                        }
                        else {
                            $.ajax({
                                url: SITEURL + "/",
                                data: {
                                    id: eventAct.id,
                                    interval: '{{ $activite->duree }}',
                                    employe_affecter: checked,
                                    type: 'modify'
                                },
                                type: "POST",
                                success: function (data) {
                                    $('#dialogModif').dialog('close');
                                    uncheckAll();

                                    displaySuccess("Plage modifiée avec succès");

                                    // Désélectionner après la sélection
                                    $('#calendar').fullCalendar('unselect');

                                    // Rafraîchir l'affichage du calendrier
                                    $('#calendar').fullCalendar('refetchEvents');
                                },
                                error: function() {
                                    $('#dialogTitre').dialog('close');
                                    displayErrorWithButton("Erreur lors de la modification de la plage. Réssayez...");
                                }
                            });
                        }
                        else {
                            displayWarning("Informations manquantes");
                        }
                    },
                    "Supprimer": function() {
                        $(this).dialog("close");
                        $( "#dialog-confirm" ).dialog({
                            resizable: false,
                            modal: true,
                            buttons: {
                                "Confirmer la suppression": function() {
                                    $.ajax({
                                        type: "POST",
                                        url: SITEURL + '/',
                                        data: {
                                                id: eventAct.id,
                                                type: 'delete'
                                        },
                                        success: function (response) {
                                            calendar.fullCalendar('removeEvents', eventAct.id);
                                            displayMessage("Plage supprimée avec succès");
                                            // Rafraîchir l'affichage du calendrier
                                            $('#calendar').fullCalendar('refetchEvents');
                                        }
                                    });
                                    $( this ).dialog( "close" );
                                    uncheckAll();
                                    $('#dialogModif').dialog("close");
                                },
                                "Annuler": function() {
                                    $( this ).dialog( "close" );
                                    uncheckAll();
                                    $('#dialogModif').dialog("open");
                                }
                            }
                        });
                    },
                    "Annuler": function() {
                        uncheckAll();
                        $(this).dialog("close");
                    }
                }
            });
        }
    },
    eventResize: function(event, delta, revertFunc) {
        if(selectable(event.start,event.end,event.id)){
            // Vérifiez que l'événement fait au moins la durée d'une activité
            let diffTime = (moment(event.end).diff(moment(event.start), 'hours').toString().length == 1 ? "0" + moment(event.end).diff(moment(event.start), 'hours') : moment(event.end).diff(moment(event.start), 'hours')) + ":" + ((moment(event.end).diff(moment(event.start), 'minutes')%60).toString().length == 1 ? "0" + (moment(event.end).diff(moment(event.start), 'minutes')%60) : moment(event.end).diff(moment(event.start), 'minutes')%60) + ":00";
            if (diffTime >= '{{ $activite->duree }}') {
                // Vérifiez si l'événement dépasse une journée
                if (moment(event.start).isSame(event.end, 'day')) {
                    var start = moment(event.start).format("YYYY-MM-DD HH:mm:ss");
                    var end = moment(event.end).format("YYYY-MM-DD HH:mm:ss");

                    $.ajax({
                        url: SITEURL + '/',
                        data: {
                            datePlage: start.split(' ')[0],
                            heureDeb: start.split(' ')[1],
                            heureFin: end.split(' ')[1],
                            id: event.id,
                            type: 'update'
                        },
                        type: "POST",
                        success: function(response) {
                            displayMessage("Plage modifiée avec succès");
                        },
                        error: function() {
                            revertFunc(); // Revert the change if the update fails
                            displayError("Erreur lors de la modification de la plage");
                        }
                    });
                } else {
                    revertFunc(); // Revert the change if the update fails
                    displayError("Les plages ne peuvent pas dépasser plusieurs jours");
                    // Désélectionner après la sélection
                    $('#calendar').fullCalendar('unselect');
                }
            } else {
                revertFunc(); // Revert the change if the update fails
                displayWarning("Impossible de modifier une plage pour qu'elle ait un interval de moins de {{ $activite->duree }} minutes");
                // Désélectionner après la sélection
                $('#calendar').fullCalendar('unselect');
            }
        } else {
            revertFunc(); // Revert the change if the update fails
            // Désélectionner après la sélection
            $('#calendar').fullCalendar('unselect');
        }
    },
});

function selectable(start, end, idEvent) {
    // Vérifiez si la date de début est passée
    if (moment().isAfter(start)) {
        displayWarning("Impossible de créer une plage dans le passé");
        return false;
    }
    // Vérifiez si la date de fin est passée
    if (moment().isAfter(end)) {
        displayWarning("Impossible de créer une plage dans le passé");
        return false;
    }
    var events = $('#calendar').fullCalendar('clientEvents');
    for (var i = 0; i < events.length; i++) {
        var event = events[i];
        if (start.isBefore(event.end) && end.isAfter(event.start) && event.id != idEvent) {
            displayWarning("Impossible de créer une plage en même temps qu'une autre");
            return false;
        }
    }

    // Vérifiez que la plage est un multiple de la durée de l'activité
    if(moment(end).diff(moment(start), 'milliseconds') % DUREE_EN_MS != 0){
        displayWarning("Impossible de créer une plage qui ne respecte pas l'interval de l'activité");
        return false;
    }

    return true;
}

var tippyPrev = tippy('.fc-prev-button', {
    content: 'Précédent',
    placement: 'top',
    theme: 'light-border',
});

var tippyNext = tippy('.fc-next-button', {
    content: 'Suivant',
    placement: 'top',
    theme: 'light-border',
});

var tippyToday = tippy('.fc-today-button', {
    content: 'Revenir à aujourd\'hui',
    placement: 'top',
    theme: 'light-border',
});

var tippyWeek = tippy('.fc-agendaWeek-button', {
    content: 'Vision Hebdomadaire',
    placement: 'top',
    theme: 'light-border',
});

var tippyDay = tippy('.fc-agendaDay-button', {
    content: 'Vision Journalière',
    placement: 'top',
    theme: 'light-border',
});

var tippyList = tippy('.fc-listMonth-button', {
    content: 'Vision Globale',
    placement: 'top',
    theme: 'light-border',
});

$('#calendar').fullCalendar('changeView', 'agendaWeek');

});

/* https://codeseven.github.io/toastr/demo.html */

function displaySuccess(message) {
    toastr.options = {
        "closeButton": true,
        "newestOnTop": true,
        "progressBar": true
    }
    toastr.success(message, 'Succés !');
}

function displayError(message) {
    toastr.options = {
        "closeButton": true,
        "newestOnTop": true,
        "progressBar": true
    }
    toastr.error(message, '! Erreur !');
}

function displayMessage(message) {
    toastr.options = {
        "closeButton": true,
        "newestOnTop": true,
        "progressBar": true
    }
    toastr.info(message, 'Information :');
}

function displayWarning(message) {
    toastr.options = {
        "closeButton": true,
        "newestOnTop": true,
        "progressBar": true
    }
    toastr.warning(message, 'Attention...');
}

function displayErrorWithButton(message) {
    toastr.options = {
        "closeButton": true,
        "newestOnTop": true,
        "progressBar": true
    }
    toastr.error(message, '! Erreur !', {
        timeOut: 0,
        extendedTimeOut: 0
    });
}
  
</script>
  
</body>
@endsection