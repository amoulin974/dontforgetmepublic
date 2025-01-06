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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/core/locales-all.global.js"></script>
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
  
<div class="container">
    <h1>Calendrier des créneaux</h1>
    <div id='calendar'></div>
</div>
   
<script>
$(document).ready(function () {

// URL dans le site
var SITEURL = "{{ url('/creneau/') }}";

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
      right: 'dayGridMonth,timeGridWeek,timeGridDay,listMonth'
    },
    buttonIcons: false, // show the prev/next text
    locale: 'fr',
    editable: true,
    events: function(start, end, timezone, callback) {
        $.ajax({
            url: SITEURL + "/calendrier",
            type: 'GET',
            success: function(data) {
                var events = [];
                $(data).each(function() {
                    events.push({
                        id: this.id,
                        title: this.id,
                        start: this.dateC,
                        end: this.dateC,
                    });
                });
                callback(events);
            },
            error: function() {
                alert('There was an error while fetching events.');
            }
        });
    },
    displayEventTime: false, // don't show the time column in list view
    weekNumbers: true,
    eventRender: function (event, element, view) {
        /* if (event.allDay === 'true') {
                event.allDay = true;
        } else {
                event.allDay = false;
        } */
    },
    selectable: true,
    selectHelper: true,
    select: function (start, end, allDay) {
        var title = prompt('Titre de l\'évènement:');
        if (title) {
            var start = $.fullCalendar.formatDate(start, "YYYY-MM-DD");
            var end = $.fullCalendar.formatDate(end, "YYYY-MM-DD");
            var heureStart = prompt('Heure de début :');
            if (heureStart) {
                var heureEnd = prompt('Heure de fin :');
                if(heureEnd) {
                    $.ajax({
                        url: SITEURL + "/ajax",
                        data: {
                            dateC: start,
                            heureDeb: '08:00:00',
                            heureFin: '20:00:00',
                            type: 'add'
                        },
                        type: "POST",
                        success: function (data) {
                            displayMessage("Event Created Successfully");

                            calendar.fullCalendar('renderEvent', // eventRender
                                {
                                    id: data.id,
                                    dateC: start,
                                    heureDeb: '08:00:00',
                                    heureFin: '20:00:00',
                                },true);

                            calendar.fullCalendar('unselect');
                        }
                    });
                }    
            }
        }
    },
    eventDrop: function (event, delta) {
        var start = $.fullCalendar.formatDate(event.start, "YYYY-MM-DD");
        var end = $.fullCalendar.formatDate(event.end, "YYYY-MM-DD");

        $.ajax({
            url: SITEURL + '/ajax',
            data: {
                dateC: start,
                heureDeb: '08:00:00',
                heureFin: '20:00:00',
                id: event.id,
                type: 'update'
            },
            type: "POST",
            success: function (response) {
                displayMessage("Event Updated Successfully");
            }
        });
    },
    eventClick: function (event) {
        var deleteMsg = confirm("Do you really want to delete?");
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
                    displayMessage("Event Deleted Successfully");
                }
            });
        }
    }
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
                start: value.dateC,
                end: value.dateC,
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

});

function displayMessage(message) {
    toastr.success(message, 'Event');
} 
  
</script>
  
</body>
@endsection