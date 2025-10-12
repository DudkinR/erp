@extends('layouts.app')
@section('content')
<div class="container">
    <h1>{{ __('My Teams & Tasks') }}</h1>
    <a href="{{ route('teams.calendar') }}" class="btn btn-primary">{{ __('View Calendar') }}</a>

    {{-- Команди --}}

   <div class="mb-4">
        <h3 class="mb-3">{{ __('Команди') }}</h3>
        <div class="d-flex flex-wrap gap-2">
                @foreach($teams as $team)
                    <a 
                        href="{{ route(request()->route()->getName(), ['team' => $team->id]) }}" 
                        class="btn btn-outline-primary"
                    >
                        {{ $team->name }}
                    </a>
                @endforeach
            </div>
        </div>
    </div>
    <div class="container">
        <h3>{{ __('Tasks') }}</h3>
        <div class="row g-3">
            @foreach($tasks as $task)
                <div class="col-md-4" @if($task->assignee_id == auth()->id()) style="border: 2px solid yellow; padding: 5px;" @endif>
                    <div class="card h-100 shadow-sm">
                        <div class="card-body">
                            <h5 class="card-title">{{ $task->title }}</h5>
                            <p class="card-text">{{ Str::limit($task->description, 100) }}</p>
                            <p class="card-text"><strong>{{ __('Creator') }}:</strong> {{ $task->creator->name ?? '-' }}</p>
                            <p class="card-text"><strong>{{ __('Assignee') }}:</strong> 
                                {{ $task->assignee_id ? \App\Models\User::find($task->assignee_id)->name : '-' }}</p>
                            <p class="card-text"><strong>{{ __('Status') }}:</strong> {{ __($task->status) }}</p>
                            {{-- Кнопка для створення звіту --}}
                            <button type="button" class="btn btn-primary btn-sm" 
                                    data-bs-toggle="modal" 
                                    data-bs-target="#reportModal_{{ $task->id }}">
                                {{ __('Report close task') }}
                            </button>
                            @if($task->creator_id == auth()->id())
                            {{-- Кнопка для створення звіту --}}
                            <button type="button" class="btn btn-secondary btn-sm" 
                                    data-bs-toggle="modal" 
                                    data-bs-target="#editTaskModal_{{ $task->id }}">
                                {{ __('Edit Task') }}
                            </button>
                            <form method="POST" action="{{ route('team-tasks.destroy', $task->id) }}" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm" 
                                        onclick="return confirm('{{ __('Are you sure you want to delete this task?') }}')">
                                    {{ __('Delete Task') }}
                                </button>
                            </form>
                            @endif
                            <button type="button" class="btn btn-info btn-sm"
                            data-bs-toggle="modal" data-bs-target="#duplicateTaskModal_{{ $task->id }}">
                                {{__('Duplicate Task')}}
                            </button>
                        </div>
                        <div class="card-footer">
                            {{-- Відображення існуючих звітів --}}
                            @foreach($task->reports ?? [] as $report)
                                <div class="border p-2 mb-1">
                                    <strong>{{ $report->user->name }}:</strong> {{ Str::limit($report->report, 50) }}
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
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
                                    <div class="mb-3 generate_times_wrapper" id="generate_times_wrapper_{{ $task->id }}" style="display:none;">
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

                @if($task->creator_id == auth()->id())
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
                                    <input type="hidden" name="team_id" value="{{$team_id}}">
                                    <div class="mb-3">
                                        <label for="assignee_id" class="form-label assignee-select">{{ __('Assignee') }}</label>
                                        <select class="form-select" name="assignee_id" id="assignee_id">
                                            <option value="">{{ __('Unassigned') }}</option>
                                            @foreach($teams->where('id', $team_id)->first()->users as $user)
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
                                    <div class="mb-3 generate_times_wrapper" id="generate_times_wrapper" style="display:none;">
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

                {{-- Модальне вікно для створення звіту --}}
                <div class="modal fade" id="reportModal_{{ $task->id }}" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog">
                        <form method="POST" action="{{ route('team-task-reports.store') }}" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="task_id" value="{{ $task->id }}">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">{{ __('Add Report for') }} {{ $task->title }}</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body">
                                    <div class="mb-3">
                                        <label for="report_{{ $task->id }}" class="form-label">{{ __('Report') }}</label>
                                        <textarea class="form-control" id="report_{{ $task->id }}" 
                                                name="report" rows="4" required>{{__('Completed')}}</textarea>
                                    </div>
                                    <div class="mb-3">
                                        <label for="attachment_{{ $task->id }}" class="form-label">{{ __('Attachment') }}</label>
                                        <input type="text" class="form-control" id="attachment_{{ $task->id }}" name="attachment">
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="submit" class="btn btn-success">{{ __('Submit Report') }}</button>
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('Close') }}</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
        @endforeach
    </div>
</div>
    <hr>

    {{-- Кнопка модалки --}}
    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createTaskModal">
        {{ __('Create Task') }}
    </button>

    {{-- Модальне вікно для створення завдання --}}
    <div class="modal fade" id="createTaskModal" tabindex="-1" aria-labelledby="createTaskModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form method="POST" action="{{ route('team-tasks.store') }}">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="createTaskModalLabel">{{ __('New Task') }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="title" class="form-label">{{ __('Title') }}</label>
                            <input type="text" class="form-control" name="title" id="title" required>
                        </div>
                        <input type="hidden" name="team_id" value="{{$team_id}}">
                        <div class="mb-3">
                            <label for="assignee_id" class="form-label assignee-select">{{ __('Assignee') }}</label>
                            <select class="form-select" name="assignee_id" id="assignee_id">
                                <option value="">{{ __('Unassigned') }}</option>
                                @foreach($team->users as $user)
                                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="description" class="form-label">{{ __('Description') }}</label>
                            <textarea class="form-control" name="description" rows="3"></textarea>
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
                         <div class="mb-3 generate_times_wrapper"  style="display:none;">
                            <label for="generate_times" class="form-label">{{ __('Generate How Many Times') }}</label>
                            <input type="number" class="form-control" name="generate_times"  min="1" value="1">
                        </div>
                    </div>
                    
                     
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success">{{ __('Save Task') }}</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('Close') }}</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<div class="container">
    <h3>{{ __('Closed Tasks') }}</h3>
    <div class="row g-3">
        @foreach($closedTasks as $task)
            <div class="col-md-4">
                <div class="card h-100 shadow-sm bg-success text-white">
                    <div class="card-body">
                        <h5 class="card-title">{{ $task->title }}</h5>
                        <p class="card-text">{{ Str::limit($task->description, 100) }}</p>
                        <p class="card-text"><strong>{{ __('Creator') }}:</strong> {{ $task->creator->name ?? '-' }}</p>
                        <p class="card-text"><strong>{{ __('Assignee') }}:</strong> 
                            {{ $task->assignee_id ? \App\Models\User::find($task->assignee_id)->name : '-' }}</p>
                        <p class="card-text"><strong>{{ __('Status') }}:</strong> {{ __($task->status) }}</p>
                    </div>
                    <div class="card-footer">
                        {{-- Відображення існуючих звітів --}}
                        @foreach($task->reports ?? [] as $report)
                            <div class="border p-2 mb-1">
                                <strong>{{ $report->user->name }}:</strong> {{ Str::limit($report->report, 50) }}
                            </div>
                        @endforeach
                    
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
<script>
  const tasks = @json($tasks);
document.addEventListener('DOMContentLoaded', function () {
    const typeRadios = document.querySelectorAll('input[name="type"]');
    const generateWrappers = document.querySelectorAll('.generate_times_wrapper');
    const generateInputs = document.querySelectorAll('.generate_times');

    function updateGenerateTimes() {
        const selected = document.querySelector('input[name="type"]:checked');
        if (!selected) return;

        if (selected.value !== 'once' && selected.value !== 'custom') {
            generateWrappers.forEach(wrapper => wrapper.style.display = 'block');
            generateInputs.forEach(input => {
               // const times = prompt("{{ __('How many times should this repeat?') }}", 1);
                input.value = times && !isNaN(times) && times > 0 ? parseInt(times) : 1;
            });
        } else {
            generateWrappers.forEach(wrapper => wrapper.style.display = 'none');
            generateInputs.forEach(input => input.value = 1);
        }
    }

    typeRadios.forEach(radio => radio.addEventListener('change', updateGenerateTimes));

    // ініціалізація при завантаженні
    updateGenerateTimes();
});

</script>
@endsection
