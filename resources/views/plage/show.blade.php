@extends('base')

@section('title_base', __('Slot settings for business #') . $entreprise -> id)
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
        <h1>{{__("Calendar with slots of")}} {{ $entreprise->libelle }}</h1>
        <br/>
    </div>
    <h4>Vous ({{ Auth::user()->nom }} {{ Auth::user()->prenom }})</h4><br>
    <div id='calendar'></div>

    <!-- Popup Dialog Suppression -->
    <div id="dialog-confirm" title="{{__("Do you want to report your unavailability ?")}}" style="display:none;">
        <p><span class="ui-icon ui-icon-alert" style="float:left;"></span>{{__("This event will be permanently deleted. Continue?")}}</p>
    </div>
</div>
   
<script>
$(document).ready(function () {

// VARIABLES GLOBALES
// URL dans le site
var SITEURL = "{{ url('/parametrage/plage/') }}";
var couleurPasses = 'red';
var couleurAjd = 'green';
var curseurUnclickable = 'not-allowed';
var curseurClickable = 'pointer';

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
      right: 'agendaWeek,agendaDay,listMonth'
    },
    buttonIcons: false, // show the prev/next text
    locale: 'fr',
    /* initialView: 'agendaWeek', */
    editable: false,
    allDaySlot: false,
    events: function(start, end, timezone, callback) {
        $.ajax({
            url: SITEURL + "/" + {{ $entreprise->id }} + "/look/" + {{ Auth::user()->id }} + "/",
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
                        title: "Votre plage pour : ",
                        start: start_datetime,
                        end: end_datetime,
                        interval: this.interval,
                        activites: actToPush,
                    });
                });
                callback(events);
            },
            error: function() {
                displayError("{{__('Event recovery error')}}");
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
            element.css('cursor', curseurClickable);
            element.find('.fc-list-item-title').append("<span class=\"intervEvent\">" + event.activites + "</span>");
        }
        
    },
    selectable: false,
    selectHelper: false,
    eventClick: function (event) {
        var eventAct = event;
        // Vérifier que l'event n'est pas passé
        if (moment(eventAct.end).isBefore(moment())) {
            displayWarning("Vous ne pouvez pas modifier un évènement passé");
        }
        else{
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
                            displayMessage("{{__('Event successfully deleted'}}");
                        },
                        error: function() {
                            displayError("{{__('Availability deleting error'}}");
                        }
                    });
                    $( this ).dialog( "close" );
                },
                "Annuler": function() {
                    $( this ).dialog( "close" );
                }
            }
        });}
    },
});

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
    toastr.success(message, '{{__("Success!")}}');
}

function displayError(message) {
    toastr.options = {
        "closeButton": true,
        "newestOnTop": true,
        "progressBar": true
    }
    toastr.error(message, '! {{__("Error")}} !');
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
    toastr.error(message, '! {{__("Error")}} !', {
        timeOut: 0,
        extendedTimeOut: 0
    });
}
  
</script>
  
</body>
@endsection