@extends('base')

@section('title', 'Paramétrage des plages de l\'entreprise n°' . $entreprise -> id)
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
    <h2>Calendrier des plages de {{ $entreprise->libelle }}</h2>
    <h3>Activité : {{ $activite->libelle }}</h3>
    <div id='calendar'></div>

    <!-- Popup Dialog -->
    <div id="dialogTitre" title="Ajout d'un évènement" style="display:none;">
        <form>
            <label for="employe">Quel employé chosir ?</label>
            <select name="employe" id="employe" class="text ui-widget-content ui-corner-all">
                <option value="{{ Auth::user()->id }}">{{ Auth::user()->nom }} {{ Auth::user()->prenom }}(Vous)</option>
                @foreach(App\Models\User::where('id',$entreprise->travailler_users()->wherePivot('idActivite',$activite->id)->pluck('idUser')) as $employe)
                    <option value="{{ $employe->id }}">{{ $employe->nom }} {{ $employe->prenom }}</option>
                @endforeach
              </select><br><br>
            {{--<label for="interv">Interval de début d'activités :</label>
            <select name="interv" id="interv" class="text ui-widget-content ui-corner-all">
                <option value="00:05:00">5 min</option>
                <option value="00:10:00">10 min</option>
                <option value="00:15:00">15 min</option>
                <option value="00:20:00">20 min</option>
                <option value="00:25:00">25 min</option>
                <option value="00:30:00">30 min</option>
                <option value="00:35:00">35 min</option>
                <option value="00:40:00">40 min</option>
                <option value="00:45:00">45 min</option>
                <option value="00:50:00">50 min</option>
                <option value="00:55:00">55 min</option>
                <option value="01:00:00">1 h</option>
              </select>--}}
            <p><strong>Interval entre chaque début d'activité :</strong> {{ $activite->duree }}</p>
        </form>
    </div>

    <!-- Popup Dialog Modif -->
    <div id="dialogModif" title="Ajout d'un évènement" style="display:none;">
        <form>
            <label for="employeModif">Quel employé chosir ?</label>
            <select name="employeModif" id="employeModif" class="text ui-widget-content ui-corner-all">
                <option value="{{ Auth::user()->id }}">{{ Auth::user()->nom }} {{ Auth::user()->prenom }} (Vous)</option>
                @foreach(App\Models\User::where('id',$entreprise->travailler_users()->wherePivot('idActivite',$activite->id)->pluck('idUser')) as $employe)
                    <option value="{{ $employe->id }}">{{ $employe->nom }} {{ $employe->prenom }}</option>
                @endforeach
              </select><br><br>
            {{--<label for="intervModif">Interval de débuts d'activités :</label>
            <select name="intervModif" id="intervModif" class="text ui-widget-content ui-corner-all">
                <option value="00:05:00">5 min</option>
                <option value="00:10:00">10 min</option>
                <option value="00:15:00">15 min</option>
                <option value="00:20:00">20 min</option>
                <option value="00:25:00">25 min</option>
                <option value="00:30:00">30 min</option>
                <option value="00:35:00">35 min</option>
                <option value="00:40:00">40 min</option>
                <option value="00:45:00">45 min</option>
                <option value="00:50:00">50 min</option>
                <option value="00:55:00">55 min</option>
                <option value="01:00:00">1 h</option>
              </select>--}}
              <p><strong>Interval entre chaque début d'activité :</strong> {{ $activite->duree }}</p>
        </form>
    </div>

    <!-- Popup Dialog Suppression -->
    <div id="dialog-confirm" title="Voulez-vous vraiment supprimer ?" style="display:none;">
        <p><span class="ui-icon ui-icon-alert" style="float:left;"></span>Cet évènement sera définitivement supprimé. Voulez-vous continuer ?</p>
    </div>
</div>

<script>
$(document).ready(function () {

// VARIABLES GLOBALES
// URL dans le site
var SITEURL = "{{ url('/entreprise/') }}";
SITEURL = SITEURL + "/" + {{ $entreprise->id }} + "/services/" + {{ $activite->id }} + "/plage";
var couleurPasses = 'red';
var couleurAjd = 'green';

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
                displayError("Erreur lors de la récupération des évènements");
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
    selectable: true,
    selectHelper: true,
    select: function (start, end, allDay) {
        // Vérifiez si l'événement est sur la même journée
        if (selectable(start,end,true)) {
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
                                });
                            },
                    buttons: {
                        "Ajouter": function() {
                            var employe = $('#employe').val();
                            //var interv = $('#interv').val();
                            if (employe /* && interv */) {
                                $.ajax({
                                    url: SITEURL + "/",
                                    data: {
                                        datePlage: start.split(' ')[0],
                                        heureDeb: start.split(' ')[1],
                                        heureFin: end.split(' ')[1],
                                        interval: '{{ $activite->duree }}',
                                        entreprise_id: {{ $entreprise->id }},
                                        employe_affecter: employe,
                                        type: 'add'
                                    },
                                    type: "POST",
                                    success: function (data) {
                                        $('#dialogTitre').dialog('close');
                                        displaySuccess("Évènement ajouté avec succès");

                                        // Désélectionner après la sélection
                                        $('#calendar').fullCalendar('unselect');

                                        // Rafraîchir l'affichage du calendrier
                                        $('#calendar').fullCalendar('refetchEvents');
                                    },
                                    error: function() {
                                        displayError("Erreur lors de l'ajout de l'évènement. Réssayez...");
                                    }
                                });
                            }
                            else {
                                displayWarning("Informations manquantes");
                            }
                            //$(this).dialog("close");
                        },
                        "Annuler": function() {
                            $('#interv').val('00:05:00');
                            $(this).dialog("close");
                        }
                    }
                });
            } else {
                displayError("Impossible de créer un évènement sur plusieurs jours");
                // Désélectionner après la sélection
                $('#calendar').fullCalendar('unselect');
            }
        }
        else{
            // Désélectionner après la sélection
            $('#calendar').fullCalendar('unselect');
        }
    },
    eventDrop: function (event, delta) {
        if(selectable(event.start,event.end,event.id)){
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
                        displayMessage("Évènement modifié avec succès");
                    }
                });
            } else {
                displayError("Les évènements ne peuvent pas dépasser plusieurs jours");
                // Désélectionner après la sélection
                $('#calendar').fullCalendar('unselect');
            }
        } else {
            // Désélectionner après la sélection
            $('#calendar').fullCalendar('unselect');
            // Rafraîchir l'affichage du calendrier
            $('#calendar').fullCalendar('refetchEvents');
        }
    },
    eventClick: function (event) {
        var eventAct = event;
        $('#dialogModif').dialog({
            modal: true,
            closeOnEscape: true,
                    open: function(event, ui) {
                        //$('#eventTitleModif').val(eventAct.title ? eventAct.title : 'Titre de l\'évènement');
                        //$('#intervModif').val(eventAct.interval ? eventAct.interval : 1);
                        $('.ui-widget-overlay').bind('click', function(){
                            $('#dialogModif').dialog('close');
                        });
                    },
            buttons: {
                "Modifier": function() {
                    var employe = $('#employeModif').val();
                    //var interv = $('#intervModif').val();
                    if (employe/*  && interv */) {
                        $.ajax({
                            url: SITEURL + "/",
                            data: {
                                id: eventAct.id,
                                interval: '{{ $activite->duree }}',
                                employe_affecter: employe,
                                type: 'modify'
                            },
                            type: "POST",
                            success: function (data) {
                                $('#dialogModif').dialog('close');

                                displaySuccess("Évènement modifié avec succès");

                                // Désélectionner après la sélection
                                $('#calendar').fullCalendar('unselect');

                                // Rafraîchir l'affichage du calendrier
                                $('#calendar').fullCalendar('refetchEvents');
                            },
                            error: function() {
                                $('#dialogTitre').dialog('close');
                                displayErrorWithButton("Erreur lors de la modification de l'évènement. Réssayez...");
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
                                        displayMessage("Évènement supprimé avec succès");
                                    }
                                });
                                $( this ).dialog( "close" );
                                $('#dialogModif').dialog("close");
                            },
                            "Annuler": function() {
                                $( this ).dialog( "close" );
                                $('#dialogModif').dialog("open");
                            }
                        }
                    });
                },
                "Annuler": function() {
                    $(this).dialog("close");
                }
            }
        });
    },
    eventResize: function(event, delta, revertFunc) {
        if(selectable(event.start,event.end,event.id)){
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
                        displayMessage("Évènement modifié avec succès");
                    },
                    error: function() {
                        revertFunc(); // Revert the change if the update fails
                        displayError("Erreur lors de la modification de l'évènement");
                    }
                });
            } else {
                revertFunc(); // Revert the change if the update fails
                displayError("Les évènements ne peuvent pas dépasser plusieurs jours");
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