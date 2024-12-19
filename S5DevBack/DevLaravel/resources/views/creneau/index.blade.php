@extends('base')

@section('title', 'Paramétrage des créneaux')
@section('creneau_active', 'active')

@section('content')
<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
  
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.9.0/fullcalendar.css" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.24.0/moment.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.9.0/fullcalendar.js"></script>
    <!-- <link rel="stylesheet" href="{{ asset('css/base.css') }}"> -->
  
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" />
</head>
<body>
  
<div class="container">
    <h1>Calendrier des créneaux</h1>
    <div id='calendar'></div>
</div>
   
<script>
$(document).ready(function () {
   
var SITEURL = "{{ url('/creneau/') }}";
  
$.ajaxSetup({
    headers: {
    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

var calendar = $('#calendar').fullCalendar({
                    editable: true,
                    events: SITEURL + "/calendrier",
                    displayEventTime: false,
                    editable: true,
                    eventRender: function (event, element, view) {
                        if (event.allDay === 'true') {
                                event.allDay = true;
                        } else {
                                event.allDay = false;
                        }
                    },
                    selectable: true,
                    selectHelper: true,
                    select: function (start, end, allDay) {
                        var title = prompt('Event Title:');
                        if (title) {
                            var start = $.fullCalendar.formatDate(start, "YYYY-MM-DD");
                            var end = $.fullCalendar.formatDate(end, "YYYY-MM-DD");
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
                                            allDay: allDay
                                        },true);
  
                                    calendar.fullCalendar('unselect');
                                }
                            });
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
 
});

function displayMessage(message) {
    toastr.success(message, 'Event');
} 
  
</script>
  
</body>
@endsection