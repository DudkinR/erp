@extends('layouts.app')
@section('content')
<div class="container">
    <h1>{{ __('My Teams & Tasks') }}</h1>
    <a href="{{ route('teams.calendar') }}" class="btn btn-primary">{{ __('View Calendar') }}</a>

    {{-- Команди --}}
    <div class="mb-3">
        <h3>{{ __('Teams') }}</h3>
        <ul>
            @foreach($teams as $team)
                <li>{{ $team->name }}</li>
            @endforeach
        </ul>
    </div>

    <div class="container">
    <h3>{{ __('Tasks') }}</h3>
    <div class="row g-3">
        @foreach($tasks as $task)
            <div class="col-md-4">
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
                        <div class="mb-3">
                            <label for="team_id" class="form-label">{{ __('Team') }}</label>
                            <select class="form-select" name="team_id" id="team_id" required>
                                @if($teams->count() > 1)
                                <option value="">{{ __('Select a team') }}</option>
                                @endif
                                @foreach($teams as $team)
                                    <option value="{{ $team->id }}">{{ $team->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="assignee_id" class="form-label">{{ __('Assignee') }}</label>
                            <select class="form-select" name="assignee_id" id="assignee_id">
                                <option value="">{{ __('Unassigned') }}</option>
                            </select>
                        </div>

                        <script>
                            document.addEventListener('DOMContentLoaded', function () {
                            const teams = @json($teams->map(function($team) {
                                return [
                                    'id' => $team->id,
                                    'members' => $team->users->map(fn($u) => ['id' => $u->id, 'name' => $u->name])
                                ];
                            }));

                            const teamSelect = document.getElementById('team_id');
                            const assigneeSelect = document.getElementById('assignee_id');

                            function updateAssignees(teamId) {
                                assigneeSelect.innerHTML = '<option value="">{{ __("Unassigned") }}</option>';

                                if (!teamId) return;

                                const team = teams.find(t => t.id === parseInt(teamId));
                                if (team) {
                                    team.members.forEach(member => {
                                        const option = document.createElement('option');
                                        option.value = member.id;
                                        option.textContent = member.name;
                                        assigneeSelect.appendChild(option);
                                    });
                                }
                            }

                            // слухаємо зміну
                            teamSelect.addEventListener('change', function () {
                                updateAssignees(this.value);
                            });

                            // якщо тільки одна команда — одразу заповнити список
                            if (teamSelect.options.length === 1 || teamSelect.value) {
                                updateAssignees(teamSelect.value);
                            }
                        });
                        </script>


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
                         <div class="mb-3" id="generate_times_wrapper" style="display:none;">
                            <label for="generate_times" class="form-label">{{ __('Generate How Many Times') }}</label>
                            <input type="number" class="form-control" name="generate_times" id="generate_times" min="1" value="1">
                        </div>
                    </div>
                    <script>
                        document.addEventListener('DOMContentLoaded', function() {
                            const typeRadios = document.querySelectorAll('input[name="type"]');
                            const generateWrapper = document.getElementById('generate_times_wrapper');
                            const generateInput = document.getElementById('generate_times');

                            function updateGenerateTimes() {
                                const selectedType = document.querySelector('input[name="type"]:checked').value;
                                if(selectedType !== 'once' && selectedType !== 'custom') {
                                    // показати поле, якщо тип не once і не custom
                                    generateWrapper.style.display = 'block';
                                    // можна запитати кількість повторів через prompt, або залишити значення 1
                                    const times = prompt("{{ __('How many times should this repeat?') }}", 1);
                                    generateInput.value = times && !isNaN(times) && times > 0 ? parseInt(times) : 1;
                                } else {
                                    // приховати поле для once або custom
                                    generateWrapper.style.display = 'none';
                                    generateInput.value = 1;
                                }
                            }

                            typeRadios.forEach(radio => radio.addEventListener('change', updateGenerateTimes));

                            // ініціалізація при завантаженні
                            updateGenerateTimes();
                        });
                        </script>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success">{{ __('Save Task') }}</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('Close') }}</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
