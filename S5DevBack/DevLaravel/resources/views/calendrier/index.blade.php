@extends('base')

@section('title', 'Paramétrage des créneaux')
@section('creneau_active', 'active')

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
</head>
<body>
  
<div class="containerCalendar">
    <h1>Calendrier des créneaux</h1>
    <div id='calendar'></div>
</div>
   
<script>
$(document).ready(function () {

// VARIABLES GLOBALES
// URL dans le site
var SITEURL = "{{ url('/calendrier/') }}";
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
      right: 'month,agendaWeek,agendaDay,listMonth'
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
                    start_datetime = this.dateRdv.split('T')[0] + 'T' + this.heureDeb + '.000000Z';
                    end_datetime = this.dateRdv.split('T')[0] + 'T' + this.heureFin + '.000000Z';
                    if (this.heureFin == '00:00:00') {
                        end_datetime = this.dateRdv.split('T')[0] + 'T' + '23:59:59' + '.000000Z';
                    }
                    events.push({
                        id: this.id,
                        title: this.id,
                        start: start_datetime,
                        end: end_datetime,
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
    /* weekText : "S",
    weekNumberFormat: { week: 'narrow' }, */
    /* buttonText: {
        prev
        today:    'today',
        month:    'month',
        week:     'week',
        day:      'day',
        list:     'list'
    }, */
    /* businessHours: [ // specify an array instead
        {
            daysOfWeek: [ 1, 2, 4, 5 ],
            startTime: '08:00',
            endTime: '17:00'
        },
        {
            daysOfWeek: [ 3 ],
            startTime: '08:30',
            endTime: '12:00'
        }
    ], */
    eventRender: function(event, element) {
        if (moment(event.end).isBefore(moment())) {
            element.css('background-color', couleurPasses); // Couleur pour les événements passés
            element.css('border-color', couleurPasses);
        } else if (moment(event.start).isSame(moment(), 'day')) {
            element.css('background-color', couleurAjd); // Couleur pour les événements futurs
            element.css('border-color', couleurAjd);
        }
    },
    selectable: true,
    selectHelper: true,
    select: function (start, end, allDay) {
        // Cas où le système (je ne sais pour quelle raison) choisis la date du lendemain comme date de fin pourprendre l'entièreté de la journée
        if($('#calendar').fullCalendar('getView').type == 'month') {
            end = end.subtract(1, 'days');
            start = start.add(8, 'hours');
            end = end.add(20, 'hours');
        }
        // Vérifiez si l'événement dépasse une journée
        if (moment(start).isSame(end, 'day')) {
            var title = prompt('Titre de l\'évènement:');
            if (title) {
                var start = $.fullCalendar.formatDate(start, "YYYY-MM-DD HH:mm:ss");
                var end = $.fullCalendar.formatDate(end, "YYYY-MM-DD HH:mm:ss");
                $.ajax({
                    url: SITEURL + "/ajax",
                    data: {
                        dateRdv: start.split(' ')[0],
                        heureDeb: start.split(' ')[1],
                        heureFin: end.split(' ')[1],
                        type: 'add'
                    },
                    type: "POST",
                    success: function (data) {
                        displaySuccess("Évènement ajouté avec succès");

                        calendar.fullCalendar('renderEvent', // eventRender
                            {
                                id: data.id,
                                dateRdv: start,
                                heureDeb: start.split(' ')[1],
                                heureFin: end.split(' ')[1],
                            },true);

                        // Désélectionner après la sélection
                        $('#calendar').fullCalendar('unselect');

                        // Rafraîchir l'affichage du calendrier
                        $('#calendar').fullCalendar('refetchEvents');
                    }
                });
            }
        } else {
            displayError("Impossible de créer un évènement sur plusieurs jours");
        }
    },
    eventDrop: function (event, delta) {
        // Vérifiez si l'événement dépasse une journée
        if (moment(event.start).isSame(event.end, 'day')) {
            var start = $.fullCalendar.formatDate(event.start, "YYYY-MM-DD HH:mm:ss");
            var end = event.end ? $.fullCalendar.formatDate(event.end, "YYYY-MM-DD HH:mm:ss") : start;
            $.ajax({
                url: SITEURL + '/ajax',
                data: {
                    dateRdv: start.split(' ')[0],
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
        }
    },
    eventClick: function (event) {
        var deleteMsg = confirm("Voulez-vous vraiment supprimer cet évènement ?");
        if (deleteMsg) {
            $.ajax({
                type: "POST",
                url: SITEURL + '/ajax',
                data: {
                        id: event.id,
                        type: 'delete'
                },
                success: function (response) {
                    calendar.fullCalendar('removeEvents', event.id);
                    displayMessage("Évènement supprimé avec succès");
                }
            });
        }
    },
    eventResize: function(event, delta, revertFunc) {
        // Vérifiez si l'événement dépasse une journée
        if (moment(event.start).isSame(event.end, 'day')) {
            var start = moment(event.start).format("YYYY-MM-DD HH:mm:ss");
            var end = moment(event.end).format("YYYY-MM-DD HH:mm:ss");

            $.ajax({
                url: SITEURL + '/ajax',
                data: {
                    dateRdv: start.split(' ')[0],
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
        }
    },
});

/* // Récupération des événements existants
$.ajax({
    url: SITEURL + "/ajax",
    data: {
        type: 'get'
    },
    type: "POST",
    success: function (response) {
        var events = [];
        $.each(response, function (index, value) {
            events.push({
                title: value.id,
                start: value.dateRdv,
                end: value.dateRdv,
            });
        });
        calendar.fullCalendar('renderEvents', events, true);
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

var tippyMonth = tippy('.fc-month-button', {
    content: 'Vision Mensuelle',
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
  
</script>
  
</body>
@endsection