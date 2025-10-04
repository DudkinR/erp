@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">{{ __('Календар команди') }}</h1>

    <a href="{{ route('teams.show') }}" class="btn btn-secondary mb-3">{{ __('Назад до команди') }}</a>

    <!-- Фільтр по статусу -->
    <div class="mb-3">
        <label for="statusFilter" class="form-label">{{ __('Фільтр за статусом') }}</label>
        <select id="statusFilter" class="form-select w-25">
            <option value="">{{ __('Всі') }}</option>
            <option value="completed">{{ __('Виконані') }}</option>
            <option value="in_progress">{{ __('В процесі') }}</option>
            <option value="pending">{{ __('Очікують') }}</option>
        </select>
    </div>

    <div id="calendar"></div>

    <!-- Список завдань -->
    <h3 class="mt-5">{{ __('Список завдань') }}</h3>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>{{ __('Назва') }}</th>
                <th>{{ __('Статус') }}</th>
                <th>{{ __('Початок') }}</th>
                <th>{{ __('Кінець') }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach($tasks as $task)
                <tr>
                    <td>{{ $task->title }}</td>
                    <td>
                        @if($task->status === 'completed')
                            <span class="badge bg-success">{{ __('Виконано') }}</span>
                        @elseif($task->status === 'in_progress')
                            <span class="badge bg-primary">{{ __('В процесі') }}</span>
                        @else
                            <span class="badge bg-warning">{{ __('Очікує') }}</span>
                        @endif
                    </td>
                    <td>{{ $task->start_at }}</td>
                    <td>{{ $task->due_at }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

<!-- FullCalendar -->
<link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/locales/uk.global.min.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        let calendarEl = document.getElementById('calendar');
        let tasks = @json($tasks);

        // Формування подій
        let events = tasks.map(task => ({
            id: task.id,
            title: task.title,
            start: task.start_at,
            end: task.due_at,
            status: task.status,
            reports: task.reports, // <-- додаємо сюди
            color: task.status === 'completed' ? 'green' :
                task.status === 'in_progress' ? 'blue' : 'orange'
        }));

        // Ініціалізація календаря
        let calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            locale: 'uk',
            dayMaxEvents: true,
            events: events,

            eventDidMount: function(info) {
                new bootstrap.Tooltip(info.el, {
                    title: "Завдання: " + info.event.title +
                           "\nСтатус: " + info.event.extendedProps.status +
                           "\nПочаток: " + info.event.start.toLocaleString() +
                           (info.event.end ? "\nКінець: " + info.event.end.toLocaleString() : ''),
                    placement: 'top',
                    trigger: 'hover',
                    container: 'body'
                });
            },

            eventClick: function(info) {
                let reports = info.event.extendedProps.reports;
                let text = "Звіти:\n";
                if (reports && reports.length > 0) {
                    reports.forEach(r => {
                        text += `- ${r.user_id}: ${r.report} (${r.created_at})\n`;
                    });
                } else {
                    text += "Немає звітів.";
                }

                alert(text); // можна замінити на Bootstrap Modal
            }
        });

        calendar.render();

        // Фільтр по статусу
        document.getElementById('statusFilter').addEventListener('change', function() {
            let selected = this.value;
            let filtered = tasks.filter(t => !selected || t.status === selected).map(task => ({
                id: t.id,
                title: t.title,
                start: t.start_at,
                end: t.due_at,
                status: t.status,
                reports: t.reports,
                color: t.status === 'completed' ? 'green' :
                       t.status === 'in_progress' ? 'blue' : 'orange'
            }));
            calendar.removeAllEvents();
            calendar.addEventSource(filtered);
        });
    });
</script>

@endsection
