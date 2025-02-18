@extends('base')

@section('title_base', 'Semaines type de ' . $entreprise -> libelle)
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

    <!-- Pour les couleur -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/mdbassit/Coloris@latest/dist/coloris.min.css"/>
    <script src="https://cdn.jsdelivr.net/gh/mdbassit/Coloris@latest/dist/coloris.min.js"></script>
</head>
<body>
  
<div class="container-calendar">
    <a href="{{ route('entreprise.show', ['entreprise' => $entreprise->id]) }}" style="left:20%; margin: 0; color:black;"><i class="fa fa-arrow-left"></i></a>
    <div class="header-profile mb-3" style="text-align: center;">
        <h2 style="color: #1167FC;"><a href="{{ route('entreprise.week.indexWeek', ['entreprise' => $entreprise->id]) }}" style="color: black; text-decoration: none; font-weight: bold;">Semaine types de {{ $entreprise->libelle }}</a> | <a href="{{ route('entreprise.day.indexDay', ['entreprise' => $entreprise->id]) }}" style="color: black; text-decoration: none;">Journées types de {{ $entreprise->libelle }}</a></h2>
        <br/>
    </div>
    <div style="text-align: center; width: 100%;">
    <input type="text" id="inputColor" data-coloris value="#3a87ad" />
    <button id="loadDayType" class="secondary-button" style="width:auto !important;"><i class="fa fa-location-arrow"></i><i class="fa fa-plus"></i> Select a day type to place</button>
    <button id="loadWeek" class="secondary-button" style="width:auto !important;"><i class="bi bi-box-arrow-in-down"></i> Select a week</button>
    <button id="newWeek" class="secondary-button" style="width:auto !important;"><i class="bi bi-calendar2-plus"></i> Create new</button>
    <button id="saveWeek" class="secondary-button" style="width:auto !important;"><i class="bi bi-save"></i> Save the type week</button>
    <button id="deleteWeek" class="btn-danger" style="width:auto !important;"><i class="bi bi-trash"></i></button></div>
    <div id='calendar'></div>
    @php
        $semaines = $entreprise->semaineTypes;
        $journees = $entreprise->journeeTypes;
    @endphp
    <script>
        var currentTypeWeek = @json($entreprise->semaineTypes->first() ? $entreprise->semaineTypes->first() : ["id" => 0, "libelle" => "", "planning" => []]);
        var semaines = @json($semaines);
    </script>

    <!-- Popup Dialog -->
    <div id="dialogTitre" title="Enregistrer votre semaine type ?" style="display:none;">
        <form>
            <label for="titre">Libellé de la plage :</label>
            <input type="text" id="titre" name="titre" style="width: 100%;"><br>
        </form>
    </div>

    <!-- Popup Dialog -->
    <div id="dialogNew" title="Créer une nouvelle semaine type ?" style="display:none;">
            <p><span class="ui-icon ui-icon-alert" style="float:left;"></span>Êtes-vous sûr(e) de vouloir créer une nouvelle semaine type ?<br/>Toute progression sera perdue.</p>
    </div>

    <!-- Popup Dialog Sélection Semaine -->
    <div id="dialogWeekSelect" title="Charger une semaine type" style="display:none;">
        <form>
            <p>Quelle semaine chosir ?</p>
            <select id="weekSelect" name="weekSelect">
                @foreach ($semaines as $semaine)
                    <option value="{{ $semaine->id }}">{{ $semaine->libelle }}</option>
                @endforeach
            </select>
        </form>
    </div>

    <!-- Popup Dialog Sélection Journée -->
    <div id="dialogDaySelect" title="Charger une journée type à placer" style="display:none;">
        <form>
            <p>Quelle journée chosir ?</p>
            <select id="daySelect" name="daySelect">
                @foreach ($journees as $jour)
                    <option value="{{ $jour->id }}">{{ $jour->libelle }}</option>
                @endforeach
            </select>
        </form>
    </div>

    <!-- Popup Dialog Placement Journée -->
    <div id="dialogDayPlace" title="Charger une journée type à placer" style="display:none;">
        <form>
            <p>Quel placer la journée chosie ?</p>
            <select id="dayPlace" name="dayPlace">
                <option value="lundi">Lundi</option>
                <option value="mardi">Mardi</option>
                <option value="mercredi">Mercredi</option>
                <option value="jeudi">Jeudi</option>
                <option value="vendredi">Vendredi</option>
                <option value="samedi">Samedi</option>
                <option value="dimanche">Dimanche</option>
            </select>
        </form>
    </div>

    <!-- Popup Dialog Suppression -->
    <div id="dialog-confirm" title="Voulez-vous vraiment supprimer ?" style="display:none;">
        <p><span class="ui-icon ui-icon-alert" style="float:left;"></span>Cette journée sera définitivement supprimé. Voulez-vous continuer ?</p>
    </div>
</div>

<script>
$(document).ready(function () {

var currentColor = $('#inputColor').val();

    // Pour tous les éléments dans la div .clr-field
    document.querySelector('.clr-field').childNodes
    .forEach(element => {
        element.style.borderRadius = '10px';
    });

    $('#inputColor').on('input', function() {
        currentColor = $('#inputColor').val();
        $('#calendar').fullCalendar('option', 'eventColor', currentColor);
    });


// VARIABLES GLOBALES
// URL dans le site
var SITEURL = "{{ url('/entreprise/') }}";
SITEURL = SITEURL + "/" + {{ $entreprise->id }} + "/week";
var DUREE_EN_MS = 1;
var semainier = {
    "lundi" : 0,
    "mardi" : 1,
    "mercredi" : 2,
    "jeudi" : 3,
    "vendredi" : 4,
    "samedi" : 5,
    "dimanche" : 6
};

// Mise en place du setup du ajax avec le token CSRF
$.ajaxSetup({
    headers: {
    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});


    $('#loadWeek').click(function() {
        // Popup pour sélectionner quelle semaine charger
        $('#dialogWeekSelect').dialog({
            modal: true,
            closeOnEscape: true,
            open: function(event, ui) {
                $('.ui-widget-overlay').bind('click', function(){
                    $('#dialogWeekSelect').dialog('close');
                });
            },
            buttons: {
                "Charger": function() {
                    var day = $('#weekSelect').val();
                    $.ajax({
                        url: SITEURL + "/",
                        data: {
                            idSemaine: day,
                            type: 'get'
                        },
                        type: 'POST',
                        success: function(data) {
                            currentTypeWeek = data[0];
                            var events = [];
                            var start_datetime;
                            var end_datetime;
                            var planningOfWeek = data[0].planning;
                            for (var dayFullLetter in planningOfWeek) {
                                var momentDay =  moment().startOf('week').add(1,'days').add(semainier[dayFullLetter],'days').format('YYYY-MM-DD');
                                var dayPlanning = planningOfWeek[dayFullLetter];
                                for (var indexPlage in planningOfWeek[dayFullLetter]) {
                                    start_datetime = momentDay + 'T' + dayPlanning[indexPlage]['start'] +':00.000000Z';
                                    end_datetime = momentDay + 'T' + dayPlanning[indexPlage]['end'] +':00.000000Z';
                                    events.push({
                                        start: start_datetime,
                                        end: end_datetime,
                                        color: currentColor,
                                    });
                                }
                            }
                            $('#calendar').fullCalendar('removeEvents');
                            $('#calendar').fullCalendar('addEventSource', events);
                            displayMessage("Journée chargée avec succès");
                        },
                        error: function() {
                            displayError("Erreur lors de la récupération des plages");
                        }
                    });
                    $('#dialogWeekSelect').dialog('close');
                },
                "Annuler": function() {
                    $(this).dialog("close");
                }
            }
        });
    });

    $('#loadDayType').click(function() {
        // Popup pour sélectionner quelle journée charger
        $('#dialogDaySelect').dialog({
            modal: true,
            closeOnEscape: true,
            open: function(event, ui) {
                $('.ui-widget-overlay').bind('click', function(){
                    $('#dialogDaySelect').dialog('close');
                });
            },
            buttons: {
                "Suivant": function() {
                    $('#dialogDayPlace').dialog({
                        modal: true,
                        closeOnEscape: true,
                        open: function(event, ui) {
                            $('#dialogDaySelect').dialog('close');
                            $('.ui-widget-overlay').bind('click', function(){
                                $('#dialogDayPlace').dialog('close');
                            });
                        },
                        buttons: {
                            "Charger": function() {
                                var dayType = $('#daySelect').val();
                                var day = $('#dayPlace').val();
                                $.ajax({
                                    url: SITEURL + "/",
                                    data: {
                                        idJournee: dayType,
                                        type: 'getDay'
                                    },
                                    type: 'POST',
                                    success: function(data) {
                                        var events = [];
                                        var start_datetime;
                                        var end_datetime;
                                        var momentDay = moment().startOf('week').add(1,'days').add(semainier[day],'days').format('YYYY-MM-DD');
                                        var planning = data[0].planning;
                                        for (var plage in planning) {
                                            start_datetime = momentDay + 'T' + planning[plage]['start'] +':00.000000Z';
                                            end_datetime = momentDay + 'T' + planning[plage]['end'] +':00.000000Z';
                                            events.push({
                                                start: start_datetime,
                                                end: end_datetime,
                                                color: planning[plage]['color'],
                                            });
                                        }
                                        $('#calendar').fullCalendar('addEventSource', events);
                                        displayMessage("Journée chargée avec succès");
                                    },
                                    error: function() {
                                        displayError("Erreur lors de la récupération des plages");
                                    }
                                });
                                $('#dialogDayPlace').dialog('close');
                                $('#dayPlace').val("lundi");
                            },
                            "Retour": function() {
                                $(this).dialog("close");
                                $('#dialogDaySelect').dialog('open');
                            }
                        }
                    });
                },
                "Annuler": function() {
                    $(this).dialog("close");
                    $('#dayPlace').val("lundi");
                }
            }
        });
    });

    $('#newWeek').click(function () {
        $('#dialogNew').dialog({
            modal: true,
            closeOnEscape: true,
            open: function(event, ui) {
                $('.ui-widget-overlay').bind('click', function(){
                    $('#dialogNew').dialog('close');
                });
            },
            buttons: {
                "Créer": function() {
                    $('#calendar').fullCalendar('removeEvents');
                    currentTypeWeek["id"] = 0;
                    $('#dialogNew').dialog('close');
                },
                "Annuler": function() {
                    $(this).dialog("close");
                }
            }
        });
    });

    $('#saveWeek').click(function () {
        $("#titre").val(currentTypeWeek["id"] == 0 ? "" : currentTypeWeek["libelle"]);
        if ($('#calendar').fullCalendar('clientEvents').length == 0) {
            displayWarning("La journée doit contenir au moins une plage");
        } else {
        $('#dialogTitre').dialog({
            modal: true,
            closeOnEscape: true,
            open: function(event, ui) {
                $('.ui-widget-overlay').bind('click', function(){
                    $('#dialogTitre').dialog('close');
                });
            },
            buttons: {
                "Enregistrer": function() {
                    var title = $('#titre').val();
                    if(title == ''){
                        displayWarning("Le titre de la journée ne peut pas être vide");
                    }
                    else {
                        if(currentTypeWeek["id"] != 0){
                            // Récupérer tous les évènements et les transformer en json pour les semaines
                            var events = $('#calendar').fullCalendar('clientEvents');
                            var planning = {};
                            for (var i = 0; i < events.length; i++) {
                                var start = events[i].start.format('HH:mm');
                                var end = events[i].end.format('HH:mm');
                                var color = events[i].color;
                                // Vérifier si le jour n'y est pas encore
                                if(!(events[i].start.format('dddd') in planning)){
                                    planning[events[i].start.format('dddd')] = {};
                                }
                                planning[events[i].start.format('dddd')][i] = {
                                    start: start,
                                    end: end,
                                    color: color
                                };
                            }
                            $.ajax({
                                url: SITEURL + "/",
                                data: {
                                    idSemaine: currentTypeWeek["id"],
                                    libelle: title,
                                    planning: planning,
                                    type: 'update'
                                },
                                type: 'POST',
                                success: function(data) {
                                    displaySuccess("Journée modifiée avec succès");
                                    $('#dialogTitre').dialog('close');
                                    // Modifier la journée dans la liste semaines
                                    for (var i = 0; i < semaines.length; i++) {
                                        if (semaines[i]["id"] == currentTypeWeek["id"]) {
                                            semaines[i]["libelle"] = title;
                                            semaines[i]["planning"] = planning;
                                        }
                                    }
                                },
                                error: function() {
                                    displayError("Erreur lors de la sauvegarde de la journée");
                                    $('#dialogTitre').dialog('close');
                                }
                            });
                        } else {
                            // Récupérer tous les évènements et les transformer en json
                            var events = $('#calendar').fullCalendar('clientEvents');
                            var planning = {};
                            for (var i = 0; i < events.length; i++) {
                                var start = events[i].start.format('HH:mm');
                                var end = events[i].end.format('HH:mm');
                                var color = events[i].color;
                                // Vérifier si le jour n'y est pas encore
                                if(!(events[i].start.format('dddd') in planning)){
                                    planning[events[i].start.format('dddd')] = {};
                                }
                                planning[events[i].start.format('dddd')][i] = {
                                    start: start,
                                    end: end,
                                    color: color
                                };
                            }
                            $.ajax({
                                url: SITEURL + "/",
                                data: {
                                    libelle: title,
                                    planning: planning,
                                    type: 'add'
                                },
                                type: 'POST',
                                success: function(data) {
                                    displaySuccess("Journée sauvegardée avec succès");
                                    $('#dialogTitre').dialog('close');
                                    currentTypeWeek = data[0];
                                    // Ajouter la nouvelle journée dans la liste semaines
                                    semaines.push(currentTypeWeek);
                                    // Ajouter la nouvelle journée dans le select
                                    $('#weekSelect').append('<option value"'+ currentTypeWeek["id"] +'">' + currentTypeWeek["libelle"] + '</option>');
                                },
                                error: function() {
                                    displayError("Erreur lors de la sauvegarde de la journée");
                                    $('#dialogTitre').dialog('close');
                                }
                            });
                        }
                    }
                },
                "Annuler": function() {
                    $(this).dialog("close");
                }
            }
        });
        }   
    });


    $('#deleteWeek').click(function () {
        $( "#dialog-confirm" ).dialog({
            resizable: false,
            modal: true,
            buttons: {
                "Confirmer la suppression": function() {
                    if(semaines.length == 0){
                        $('#calendar').fullCalendar('removeEvents');
                        $( this ).dialog( "close" );
                    } else {

                    $.ajax({
                        type: "POST",
                        url: SITEURL + '/',
                        data: {
                                idSemaine: currentTypeWeek["id"],
                                type: 'delete'
                        },
                        success: function (response) {
                            $('#calendar').fullCalendar('removeEvents');
                            displayMessage("Journée supprimée avec succès");
                            // Supprimer la journée de la liste semaines
                            for (var i = 0; i < semaines.length; i++) {
                                if (semaines[i]["id"] == currentTypeWeek["id"]) {
                                    semaines.splice(i, 1);
                                }
                            }
                            // Supprimer la journée du select
                            $('#daySelect option[value="' + currentTypeWeek["id"] + '"]').remove();
                            // Charger la première journée
                            // Vérfier si semaines n'est pas vide
                            if(semaines.length != 0) {
                            currentTypeWeek = semaines[0];
                            var events = [];
                            var start_datetime;
                            var end_datetime;
                            var planningOfWeek = data[0].planning;
                            for (var dayFullLetter in planningOfWeek) {
                                var momentDay =  moment().startOf('week').add(1,'days').add(semainier[dayFullLetter],'days').format('YYYY-MM-DD');
                                var dayPlanning = planningOfWeek[dayFullLetter];
                                for (var indexPlage in planningOfWeek[dayFullLetter]) {
                                    start_datetime = momentDay + 'T' + dayPlanning[indexPlage]['start'] +':00.000000Z';
                                    end_datetime = momentDay + 'T' + dayPlanning[indexPlage]['end'] +':00.000000Z';
                                    events.push({
                                        start: start_datetime,
                                        end: end_datetime,
                                        color: currentColor,
                                    });
                                }
                            }
                            $('#calendar').fullCalendar('addEventSource', events);
                            } else {
                                currentTypeWeek = {
                                    "id": 0,
                                    "libelle": "",
                                    "planning": {}
                                };
                            }
                        }
                    });
                    $( this ).dialog( "close" );
                    }
                },
                "Annuler": function() {
                    $( this ).dialog( "close" );
                }
            }
        });
    });

// Mise en place du calendrier
var calendar = $('#calendar').fullCalendar({
    header: {
        left: 'prev,next',
        right: 'agendaWeek,agendaDay'
    },
    validRange: {
    start: moment().startOf('week').add(1,'days'), // Limite le début de la plage de dates à la semaine actuelle
    end: moment().endOf('week').add(1,'days') // Limite la fin de la plage de dates à la semaine actuelle
    },
    buttonIcons: false, // show the prev/next text
    locale: 'fr',
    editable: true,
    events: function(start, end, timezone, callback) {
        $.ajax({
            url: SITEURL + "/",
            data: {
                idSemaine: {{ App\Models\SemaineType::where("idEntreprise",$entreprise->id)->first() ? App\Models\SemaineType::where("idEntreprise",$entreprise->id)->first()->id : 0 }},
                type: 'get'
            },
            type: 'POST',
            success: function(data) {
                if(data.length != 0){
                var events = [];
                var start_datetime;
                var end_datetime;
                var planningOfWeek = data[0].planning;
                for (var dayFullLetter in planningOfWeek) {
                    var momentDay =  moment().startOf('week').add(1,'days').add(semainier[dayFullLetter],'days').format('YYYY-MM-DD');
                    var dayPlanning = planningOfWeek[dayFullLetter];
                    for (var indexPlage in planningOfWeek[dayFullLetter]) {
                        start_datetime = momentDay + 'T' + dayPlanning[indexPlage]['start'] +':00.000000Z';
                        end_datetime = momentDay + 'T' + dayPlanning[indexPlage]['end'] +':00.000000Z';
                        events.push({
                            start: start_datetime,
                            end: end_datetime,
                            color: currentColor,
                        });
                    }
                }
                callback(events);
                } else {
                    var events = [];
                    callback(events);
                }
            },
            error: function() {
                displayError("Erreur lors de la récupération des plages");
            }
        });
    },
    eventRender: function(event, element) {
        element.css('background-color', event.color);
        element.css('border-color', event.color);
    },
    displayEventTime: true, // false -> don't show the time column in list view
    weekNumbers: false,
    columnHeader: true,
    titleFormat: 'dddd',
    columnHeaderFormat: 'dddd',
    selectable: true,
    nowIndicator: false,
    selectHelper: true,
    allDaySlot: false,
    selectOverlap: false,
    select: function (start, end, allDay) {
        // Vérifiez si l'événement est sur la même journée
        if (selectable(start,end,true)) {
                // Vérifiez si l'événement dépasse une journée
                if (moment(start).isSame(end, 'day')) {
                    var start = $.fullCalendar.formatDate(start, "YYYY-MM-DD HH:mm:ss");
                    var end = $.fullCalendar.formatDate(end, "YYYY-MM-DD HH:mm:ss");
                    // Enregistrer l'évènements dans les events clients
                    var event = {
                        start: start,
                        end: end,
                        color: currentColor
                    };
                    $('#calendar').fullCalendar('renderEvent', event, true); // stick? = true
                } else {
                    displayError("Impossible de créer une plage sur plusieurs jours");
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
        if(selectable(event.start,event.end,event.id)){
            // Vérifiez si l'événement dépasse une journée
            if (moment(event.start).isSame(event.end, 'day')) {
                // ok
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
        
    },
    eventResize: function(event, delta, revertFunc) {
        if(selectable(event.start,event.end,event.id)){
                // Vérifiez si l'événement dépasse une journée ou s'il ne chevauche pas un autre event
                if (moment(event.start).isSame(event.end, 'day') && checkChevauche(event.start, event.end, event.id)) {
                    // ok
                } else {
                    revertFunc(); // Revert the change if the update fails
                    displayError("Les plages ne peuvent pas se chevaucher");
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

function checkChevauche(start, end, idEvent) {
    var events = $('#calendar').fullCalendar('clientEvents');
    for (var i = 0; i < events.length; i++) {
        var event = events[i];
        if (start.isBefore(event.end) && end.isAfter(event.start) && event.id != idEvent) {
            return false;
        }
    }
    return true;
}

function selectable(start, end, idEvent) {
    // Vérifiez que la plage est un multiple de la durée de l'activité
    if(moment(end).diff(moment(start), 'milliseconds') % DUREE_EN_MS != 0){
        displayWarning("Impossible de créer une plage qui ne respecte pas l'intervalle de l'activité");
        return false;
    }

    return true;
}

// Callback function to execute when mutations are observed
const callback = (mutationList, observer) => {
  for (const mutation of mutationList) {
    if (mutation.type === "childList") {
        if ($('.fc-center').children().text() == 'dimanche – samedi') {
            $('.fc-center').children().text('dimanche');
        }
    } else if (mutation.type === "attributes") {
    }
  }
};

const config = { attributes: true, childList: true, subtree: true };

const observer = new MutationObserver(callback);

observer.observe($(".fc-center")[0], config);

/* $('.fc-center').children().on('DOMSubtreeModified', function() { // Deprecated
    if ($('.fc-center').children().text() == 'dimanche - samedi') {
        console.log('text changed');
        $('.fc-center').children().text('dimanche');
    }
}); */

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
    toastr.success(message, 'Succès !');
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