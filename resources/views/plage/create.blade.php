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

<div class="container-calendar">
    <a href="{{ route('entreprise.show', ['entreprise' => $entreprise->id]) }}" style="left:20%; margin: 0; color:black;"><i class="fa fa-arrow-left"></i></a>
    <div class="header-profile mb-3">
        <h1>Calendrier des plages de {{ $entreprise->libelle }}</h1>
        <br/>
    </div>
    <div style="text-align: center; width: 100%;">
    <h4>Employé : {{ $employe->nom }} {{ $employe->prenom }}</h4>
    <button id="loadDayType" class="secondary-button" style="width:auto !important;"><i class="fa fa-location-arrow"></i> <i class="fa fa-plus"></i> Select a day type to place</button>
    <button id="loadWeekType" class="secondary-button" style="width:auto !important;"><i class="fa fa-location-arrow"></i> <i class="fa fa-calendar-plus"></i> Select a week type to place</button>
    </div>
    <div id='calendar'></div>
    @php
        $actWorkedByUser = App\Models\Activite::whereIn("id",$employe->travailler_entreprises()->wherePivot("idEntreprise",$entreprise->id)->pluck("idActivite"))->get();
        $semaines = $entreprise->semaineTypes;
        $journees = $entreprise->journeeTypes;
    @endphp

    <!-- Popup Dialog -->
    <div id="dialogTitre" title="Ajout d'une plage" style="display:none;">
        <form>
            <p>Quelle activité choisir ?</p>
            <div style="width: 100%;">
                <button type="button" id="all" onclick="checkAll()" style="display:block; margin:auto; margin-bottom:1%;">Tout sélectionner</button>
            </div>
            <div id="employes" name="employes" style="overflow: auto; display:block; max-height:50%;">
                @foreach($actWorkedByUser as $activite)
                    <label for="{{ $activite->id }}"><input type="checkbox" id="{{ $activite->id }}" value="{{ $activite->id }}"> {{ $activite->libelle }}</label><br>
                @endforeach
            </div><br>
        </form>
    </div>

    <!-- Popup Dialog Modif -->
    <div id="dialogModif" title="Ajout d'une plage" style="display:none;">
        <form>
            <p>Quelle activité choisir ?</p>
            <div style="width: 100%;">
                <button type="button" id="all" onclick="checkAllModif()" style="display:block; margin:auto; margin-bottom:1%;">Tout sélectionner</button>
            </div>
                <div id="employesModif" name="employesModif" style="overflow: auto; display:block; max-height:50%;">
                @foreach($actWorkedByUser as $activite)
                    <label for="{{ $activite->libelle }}Modif"><input type="checkbox" id="{{ $activite->libelle }}Modif" value="{{ $activite->id }}"> {{ $activite->libelle }}</label><br>
                @endforeach
            </div><br>
        </form>
    </div>

    <!-- Popup Dialog Suppression -->
    <div id="dialog-confirm" title="Voulez-vous vraiment supprimer ?" style="display:none;">
        <p><span class="ui-icon ui-icon-alert" style="float:left;"></span>Cette plage sera définitivement supprimé. Voulez-vous continuer ?</p>
    </div>

    <!-- Popup Dialog Sélection Semaine -->
    <div id="dialogWeekSelect" title="Charger une semaine type" style="display:none;">
        <form>
            <p>Quelle semaine chosir ?</p>
            <p>Attention, cela écrasera les plages déjà présentes</p>
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
            <p><i class="fa fa-warning"></i>Attention, cela écrasera les plages déjà présentes</p>
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
            <p>Quel jour voulez-vous placer la journée choisie ?</p>
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
SITEURL = SITEURL + "/" + {{ $entreprise->id }} + "/services/" + {{ $employe->id }} + "/plage";

// Mise en place du setup du ajax avec le token CSRF
$.ajaxSetup({
    headers: {
    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

var couleurPasses = 'red';
var couleurAjd = 'green';
var curseurUnclickable = 'not-allowed';
var DUREE = "00:30:00";
var DUREE_EN_MS = moment.duration(DUREE).asMilliseconds();
var semainier = {
    "lundi" : 0,
    "mardi" : 1,
    "mercredi" : 2,
    "jeudi" : 3,
    "vendredi" : 4,
    "samedi" : 5,
    "dimanche" : 6
};

$('#loadWeekType').click(function() {
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
                    var momentDay = $('#calendar').fullCalendar('getDate').format('YYYY-MM-DD');
                    if (moment().isAfter(momentDay)) {
                        displayWarning("Impossible de placer une journée dans le passé");
                    }
                    else {
                        $(this).dialog("close");
                        $('#dialogTitre').dialog({
                            modal: true,
                            closeOnEscape: true,
                                open: function(event, ui) {
                                    $('.ui-widget-overlay').bind('click', function(){
                                        $('#dialogTitre').dialog('close');
                                        uncheckAll();
                                    });
                                },
                            buttons: {
                                "Ajouter": function() {
                                    if (checked.length == 0){
                                    displayWarning('Veuillez sélectionner au moins une activité.');
                                    }
                                    else {
                                        var eventsToSave = [];
                                        $.ajax({
                                            url: "{{ url('/entreprise/') }}" + "/" + {{ $entreprise->id }} + "/week" + "/",
                                            data: {
                                                idSemaine: day,
                                                type: 'get'
                                            },
                                            type: 'POST',
                                            success: function(data) {
                                                var events = [];
                                                var start_datetime;
                                                var end_datetime;
                                                var minStart_datetime = moment(momentDay + ' ' + '23:59:59', 'YYYY-MM-DD HH:mm:ss').add(6,'days');
                                                var maxEnd_datetime = moment(momentDay + ' ' + '00:00:00', 'YYYY-MM-DD HH:mm:ss');
                                                var planningOfWeek = data[0].planning;
                                                for (var dayFullLetter in planningOfWeek) {
                                                    var momentDay =  $('#calendar').fullCalendar('getDate').add(semainier[dayFullLetter],'days').format('YYYY-MM-DD');
                                                    var dayPlanning = planningOfWeek[dayFullLetter];
                                                    for (var indexPlage in planningOfWeek[dayFullLetter]) {
                                                        start_datetime = momentDay + 'T' + dayPlanning[indexPlage]['start'] +':00.000000Z';
                                                        end_datetime = momentDay + 'T' + dayPlanning[indexPlage]['end'] +':00.000000Z';
                                                        minStart_datetime = minStart_datetime < start_datetime ? minStart_datetime : start_datetime;
                                                        maxEnd_datetime = maxEnd_datetime > end_datetime ? maxEnd_datetime : end_datetime;
                                                        events.push({
                                                            start: start_datetime,
                                                            end: end_datetime,
                                                        });
                                                        eventsToSave.push({
                                                            start: start_datetime,
                                                            end: end_datetime,
                                                        });
                                                    }
                                                }
                                                var eventsAct = $('#calendar').fullCalendar('clientEvents');
                                                eventsAct.forEach(function(eventAct) {
                                                    if (moment(eventAct.end).isAfter(minStart_datetime) && moment(eventAct.start).isBefore(maxEnd_datetime)) {
                                                        $('#calendar').fullCalendar('removeEvents', eventAct.id);
                                                        $.ajax({
                                                            type: "POST",
                                                            url: SITEURL + '/',
                                                            data: {
                                                                    id: eventAct.id,
                                                                    type: 'delete'
                                                            },
                                                            success: function (response) {
                                                            }
                                                        });
                                                    }
                                                });
                                                displayMessage("Semaine chargée avec succès et plages remplacées");
                                                eventsToSave.forEach(event => {
                                                    var start = moment(event.start).subtract(1,'hours').format("YYYY-MM-DD HH:mm:ss");
                                                    var end = moment(event.end).subtract(1,'hours').format("YYYY-MM-DD HH:mm:ss");
                                                    $.ajax({
                                                        url: SITEURL + "/",
                                                        data: {
                                                            datePlage: start.split(' ')[0],
                                                            heureDeb: start.split(' ')[1],
                                                            heureFin: end.split(' ')[1],
                                                            interval: DUREE,
                                                            activites_affecter: checked,
                                                            type: 'add'
                                                        },
                                                        type: "POST",
                                                        success: function (data) {
                                                        },
                                                        error: function() {
                                                            displayError("Erreur lors de l'ajout de la plage. Réssayez...");
                                                        }
                                                    });
                                                });
                                                $('#dialogTitre').dialog('close');
                                                uncheckAll();
                                                // Désélectionner après la sélection
                                                $('#calendar').fullCalendar('unselect');
                                                // Rafraîchir l'affichage du calendrier
                                                $('#calendar').fullCalendar('refetchEvents');
                                            },
                                            error: function() {
                                                displayError("Erreur lors de la récupération des plages");
                                            }
                                        });
                                        $('#dialogWeekSelect').dialog('close');
                                    }
                                },
                                "Annuler": function() {
                                    $(this).dialog("close");
                                }
                            }
                        });
                    }
                }
            },
            "Annuler": function() {
                $(this).dialog("close");
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
                    $(this).dialog("close");
                    $('#dialogDayPlace').dialog({
                        modal: true,
                        closeOnEscape: true,
                        open: function(event, ui) {
                            $('.ui-widget-overlay').bind('click', function(){
                                $('#dialogDayPlace').dialog('close');
                            });
                        },
                        buttons: {
                            "Charger": function() {
                                var dayType = $('#daySelect').val();
                                var day = $('#dayPlace').val();
                                var momentDay = $('#calendar').fullCalendar('getDate').add(semainier[day],'days').format('YYYY-MM-DD');
                                if (moment().isAfter(momentDay)) {
                                    displayWarning("Impossible de placer une journée dans le passé");
                                }
                                else {
                                    $(this).dialog("close");
                                    $('#dialogTitre').dialog({
                                        modal: true,
                                        closeOnEscape: true,
                                                open: function(event, ui) {
                                                    $('.ui-widget-overlay').bind('click', function(){
                                                        $('#dialogTitre').dialog('close');
                                                        uncheckAll();
                                                    });
                                                },
                                        buttons: {
                                            "Ajouter": function() {
                                                if (checked.length == 0){
                                                displayWarning('Veuillez sélectionner au moins une activité.');
                                                }
                                                else {
                                                    var eventsToSave = [];
                                                    $.ajax({
                                                        url: "{{ url('/entreprise/') }}" + "/" + {{ $entreprise->id }} + "/week" + "/",
                                                        data: {
                                                            idJournee: dayType,
                                                            type: 'getDay'
                                                        },
                                                        type: 'POST',
                                                        success: function(data) {
                                                            var events = [];
                                                            var start_datetime;
                                                            var end_datetime;
                                                            var minStart_datetime = moment(momentDay + ' ' + '23:59:59', 'YYYY-MM-DD HH:mm:ss');
                                                            var maxEnd_datetime = moment(momentDay + ' ' + '00:00:00', 'YYYY-MM-DD HH:mm:ss');
                                                            var planning = data[0].planning;
                                                            for (var plage in planning) {
                                                                start_datetime = momentDay + 'T' + planning[plage]['start'] +':00.000000Z';
                                                                end_datetime = momentDay + 'T' + planning[plage]['end'] +':00.000000Z';
                                                                minStart_datetime = minStart_datetime < start_datetime ? minStart_datetime : start_datetime;
                                                                maxEnd_datetime = maxEnd_datetime > end_datetime ? maxEnd_datetime : end_datetime;
                                                                events.push({
                                                                    start: start_datetime,
                                                                    end: end_datetime,
                                                                });
                                                                eventsToSave.push({
                                                                    start: start_datetime,
                                                                    end: end_datetime,
                                                                });
                                                            }
                                                            // Retirer les plages déjà présentes sur le jour concerné
                                                            /* $('#calendar').fullCalendar('removeEvents', function(event) {
                                                                return event.start.format('YYYY-MM-DD') == momentDay;
                                                            }); */
                                                            var eventsAct = $('#calendar').fullCalendar('clientEvents');
                                                            eventsAct.forEach(function(eventAct) {
                                                                if (moment(eventAct.end).isAfter(minStart_datetime) && moment(eventAct.start).isBefore(maxEnd_datetime)) {
                                                                    $('#calendar').fullCalendar('removeEvents', eventAct.id);
                                                                    $.ajax({
                                                                        type: "POST",
                                                                        url: SITEURL + '/',
                                                                        data: {
                                                                                id: eventAct.id,
                                                                                type: 'delete'
                                                                        },
                                                                    });
                                                                    
                                                                }
                                                            });
                                                            displayMessage("Journée chargée avec succès et plages remplacées");

                                                            //$('#dialogDayPlace').dialog('close');
                                                            $('#dayPlace').val("lundi");
                                                            eventsToSave.forEach(event => {
                                                                var start = moment(event.start).subtract(1,'hours').format("YYYY-MM-DD HH:mm:ss");
                                                                var end = moment(event.end).subtract(1,'hours').format("YYYY-MM-DD HH:mm:ss");
                                                                $.ajax({
                                                                    url: SITEURL + "/",
                                                                    data: {
                                                                        datePlage: start.split(' ')[0],
                                                                        heureDeb: start.split(' ')[1],
                                                                        heureFin: end.split(' ')[1],
                                                                        interval: DUREE,
                                                                        activites_affecter: checked,
                                                                        type: 'add'
                                                                    },
                                                                    type: "POST",
                                                                    success: function (data) {
                                                                    },
                                                                    error: function() {
                                                                        displayError("Erreur lors de l'ajout de la plage. Réssayez...");
                                                                    }
                                                                });
                                                            });
                                                            $('#dialogTitre').dialog('close');
                                                            uncheckAll();
                                                            // Désélectionner après la sélection
                                                            $('#calendar').fullCalendar('unselect');
                                                            // Rafraîchir l'affichage du calendrier
                                                            $('#calendar').fullCalendar('refetchEvents');
                                                        },
                                                        error: function() {
                                                            displayError("Erreur lors de la récupération des plages");
                                                        }
                                                    });
                                                }
                                            },
                                            "Annuler": function() {
                                                uncheckAll();
                                                $(this).dialog("close");
                                                $('#dialogDayPlace').dialog('open');
                                            }
                                        }
                                    });
                                }
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
                    var actToPush = "";
                    this.activites.forEach(act => {
                        actToPush += act.libelle + ", ";
                    });
                    actToPush = actToPush.slice(0, -2);
                    events.push({
                        id: this.id,
                        title: "La plage de {{ $employe->nom }} {{ $employe->prenom }} pour : ",
                        start: start_datetime,
                        end: end_datetime,
                        interval: this.interval,
                        activites: actToPush,
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
            element.css('cursor', curseurUnclickable);
        } else if (moment(event.start).isSame(moment(), 'day')) {
            element.css('background-color', couleurAjd); // Couleur pour les événements futurs
            element.css('border-color', couleurAjd);
            element.css('cursor', curseurUnclickable);
        }
        if (event.activites) { // Si le nombre de personnes est renseigné
            element.find('.fc-title').after("<span class=\"intervEvent\">" + event.activites + "</span>");
        }

    },
    snapDuration: DUREE,
    selectable: true,
    nowIndicator: true,
    selectHelper: true,
    allDaySlot: false,
    select: function (start, end, allDay) {
        // Vérifiez si l'événement est sur la même journée
        if (selectable(start,end,true)) {
            // Vérifiez que l'événement fait au moins la durée d'une activité
            let diffTime = (moment(end).diff(moment(start), 'hours').toString().length == 1 ? "0" + moment(end).diff(moment(start), 'hours') : moment(end).diff(moment(start), 'hours')) + ":" + ((moment(end).diff(moment(start), 'minutes')%60).toString().length == 1 ? "0" + (moment(end).diff(moment(start), 'minutes')%60) : moment(end).diff(moment(start), 'minutes')%60) + ":00";
            if (diffTime >= DUREE) {
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
                                displayWarning('Veuillez sélectionner au moins une activité.');
                                }
                                else {
                                    $.ajax({
                                        url: SITEURL + "/",
                                        data: {
                                            datePlage: start.split(' ')[0],
                                            heureDeb: start.split(' ')[1],
                                            heureFin: end.split(' ')[1],
                                            interval: DUREE,
                                            activites_affecter: checked,
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
                displayWarning("Impossible de créer une plage de moins de DUREE minutes");
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
        event.activites.split(', ').forEach(act => {
            if(act.split(' ')[0] != '') {
                alreadyChecked.push(act.split(' ')[0]);
            }
        });
        alreadyChecked.forEach(actPlaced => {
            $('#'+actPlaced+"Modif").prop('checked', true);
            checked.push($('#'+actPlaced+"Modif").val());
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
                        displayWarning('Veuillez sélectionner au moins une activité.');
                        }
                        else {
                            $.ajax({
                                url: SITEURL + "/",
                                data: {
                                    id: eventAct.id,
                                    interval: DUREE,
                                    activites_affecter: checked,
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
            if (diffTime >= DUREE) {
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
                displayWarning("Impossible de modifier une plage pour qu'elle ait un intervalle de moins de DUREE minutes");
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
        displayWarning("Impossible de créer une plage qui ne respecte pas l'intervalle de l'activité");
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
