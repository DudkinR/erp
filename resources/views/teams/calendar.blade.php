@extends('layouts.app')

@section('content')
<div class="container">
    <h1>{{ __('Team Calendar') }}</h1>
    <a href="{{ route('teams.show') }}" class="btn btn-secondary">{{__('Back to Team')}}</a>
    <div id="calendar"></div>
</div>
 <!-- Підключення FullCalendar -->
    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            let calendarEl = document.getElementById('calendar');

            let tasks = @json($tasks); // Laravel віддає масив у JS

            let events = tasks.map(task => ({
                id: task.id,
                title: task.title,
                start: task.start_at, // або due_at, залежно від логіки
                end: task.due_at,
                color: task.status === 'completed' ? 'green' : 'orange',
            }));

            let calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                events: events,
                eventClick: function(info) {
                    alert("Завдання: " + info.event.title + "\nСтатус: " + info.event.extendedProps.status);
                }
            });

            calendar.render();
        });
    </script>
@endsection
