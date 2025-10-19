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
    <div class="row" id="search">
        <div class="col-md-4 mb-3">
            <input type="text" id="taskSearch" class="form-control" placeholder="{{ __('Пошук завдань...') }}">
        </div>
    </div>
    
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>{{ __('Назва') }}</th>
                <th>{{ __('Статус') }}</th>
                <th>{{ __('Початок') }}</th>
                <th>{{ __('Кінець') }}</th>
                <th>{{ __('Дії') }}</th>
            </tr>
        </thead>
        <tbody id="taskTableBody">
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
                    <td>
                        @if($task->creator_id == auth()->id())
                        <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#editTaskModal_{{ $task->id }}">
                            {{ __('Редагувати') }}
                        </button>
                        {{-- Модальне вікно для редагування завдання --}}
                <div class="modal fade" id="editTaskModal_{{ $task->id }}" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog">
                        <form method="POST" action="{{ route('team-tasks.update', $task->id) }}">
                            @csrf
                            @method('PUT')
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">{{ __('Edit Task') }}: {{ $task->title }}</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body">

                                    <input type="hidden" name="team_id" value="@foreach($teams as $team){{ $team->id }}@break; @endforeach">

                                    <div class="mb-3">
                                        <label for="title_{{ $task->id }}" class="form-label">{{ __('Title') }}</label>
                                        <input type="text" class="form-control" id="title_{{ $task->id }}" 
                                                name="title" value="{{ $task->title }}" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="assignee_id" class="form-label assignee-select">{{ __('Assignee') }}</label>
                                        <select class="form-select" name="assignee_id" id="assignee_id">
                                            <option value="">{{ __('Unassigned') }}</option>
                                            @foreach($teams->where('id', $task->team_id)->first()->users as $user)
                                                <option value="{{ $user->id }}">{{ $user->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label for="description_{{ $task->id }}" class="form-label">{{ __('Description') }}</label>
                                        <textarea class="form-control" id="description_{{ $task->id }}" 
                                                name="description" rows="3">{{ $task->description }}</textarea>
                                    </div>
            
                                    <div class="mb-3">
                                        <label for="due_date" class="form-label">{{ __('Due Date') }}</label>
                                        <input type="date" class="form-control" name="due_date"
                                        value="{{ \Carbon\Carbon::now()->format('Y-m-d') }}" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="type" class="form-label">{{ __('Type') }}</label>
                                        <div>
                                            <input type="radio" name="type" value="once" id="type_once" checked>
                                            <label for="type_once">{{ __('Once') }}</label>
                                        </div>
                                        <div>
                                            <input type="radio" name="type" value="daily" id="type_daily">
                                            <label for="type_daily">{{ __('Daily') }}</label>
                                        </div>
                                        <div>
                                            <input type="radio" name="type" value="weekly" id="type_weekly">
                                            <label for="type_weekly">{{ __('Weekly') }}</label>
                                        </div>
                                        <div>
                                            <input type="radio" name="type" value="monthly" id="type_monthly">
                                            <label for="type_monthly">{{ __('Monthly') }}</label>
                                        </div>
                                        <div>
                                            <input type="radio" name="type" value="yearly" id="type_yearly">
                                            <label for="type_yearly">{{ __('Yearly') }}</label>
                                        </div>
                                        <div>
                                            <input type="radio" name="type" value="custom" id="type_custom">
                                            <label for="type_custom">{{ __('Custom') }}</label>
                                        </div>
                                    </div>
                                    <div class="mb-3 generate_times_wrapper" id="generate_times_wrapper" >
                                        <label for="generate_times" class="form-label">{{ __('Generate How Many Times') }}</label>
                                        <input type="number" class="form-control" name="generate_times"  min="1" value="1">
                                    </div>

                                </div>
                                <div class="modal-footer">
                                    <button type="submit" class="btn btn-success">{{ __('Save Changes') }}</button>
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('Close') }}</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>  
                 @endif
                        {{-- Модальне вікно для дублювання завдання --}}
                <div class="modal fade" id="duplicateTaskModal_{{ $task->id }}" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog">
                        <form method="POST" action="{{ route('team-tasks.store') }}">
                            @csrf
                            <input type="hidden" name="title" value="{{ $task->title }}">
                            <input type="hidden" name="description" value="{{ $task->description }}">
                            <input type="hidden" name="assignee_id" value="{{ $task->assignee_id }}">
                            <input type="hidden" name="team_id" value="{{ $task->team_id }}">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">{{ __('Duplicate Task') }}: {{ $task->title }}</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body  ">
                                    <p>{{ __('Do you want to create a duplicate of this task with the same title, description, assignee, and team?') }}</p>
                                    <div class="mb-3">
                                        <label for="due_date_{{ $task->id }}" class="form-label">{{ __('Due Date') }}</label>
                                        <input type="date" class="form-control" name="due_date"
                                        value="{{ \Carbon\Carbon::now()->format('Y-m-d') }}" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="type_{{ $task->id }}" class="form-label">{{ __('Type') }}</label>
                                        <div>
                                            <input type="radio" name="type" value="once" id="type_once_{{ $task->id }}" checked>
                                            <label for="type_once_{{ $task->id }}">{{ __('Once') }}</label>
                                        </div>
                                        <div>
                                            <input type="radio" name="type" value="daily" id="type_daily_{{ $task->id }}">
                                            <label for="type_daily_{{ $task->id }}">{{ __('Daily') }}</label>
                                        </div>
                                        <div>
                                            <input type="radio" name="type" value="weekly" id="type_weekly_{{ $task->id }}">
                                            <label for="type_weekly_{{ $task->id }}">{{ __('Weekly') }}</label>
                                        </div>
                                        <div>
                                            <input type="radio" name="type" value="monthly" id="type_monthly_{{ $task->id }}">
                                            <label for="type_monthly_{{ $task->id }}">{{ __('Monthly') }}</label>
                                        </div>
                                        <div>
                                            <input type="radio" name="type" value="yearly" id="type_yearly_{{ $task->id }}">
                                            <label for="type_yearly_{{ $task->id }}">{{ __('Yearly') }}</label>
                                        </div>
                                        <div>
                                            <input type="radio" name="type" value="custom" id="type_custom_{{ $task->id }}">
                                            <label for="type_custom_{{ $task->id }}">{{ __('Custom') }}</label>
                                        </div>
                                    </div>
                                    <div class="mb-3 generate_times_wrapper" id="generate_times_wrapper_{{ $task->id }}">
                                        <label for="generate_times_{{ $task->id }}" class="form-label">{{ __('Generate How Many Times') }}</label>
                                        <input type="number" class="form-control generate_times" name="generate_times"  min="1" value="1">
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="submit" class="btn btn-success">{{ __('Create Duplicate') }}</button>
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('Close') }}</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                        <button type="button" class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#duplicateTaskModal_{{ $task->id }}">
                            {{ __('Дублювати') }}
                        </button>
                        
                    </td>
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

    // Пошук завдань в таблиці 
    document.getElementById('taskSearch').addEventListener('input', function() {
        let query = this.value.toLowerCase();
        let rows = document.querySelectorAll('#taskTableBody tr');
        rows.forEach(row => {
            let title = row.cells[0].textContent.toLowerCase();
            if (title.includes(query)) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    });

</script>

@endsection
