@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h1>{{__('Tasks')}}</h1>
                <form method="POST" action="{{ route('tasks.store') }}">
                     @php 
                        $projects = \App\Models\Project::all();
                        $stages = \App\Models\Stage::all();
                        $steps = \App\Models\Step::all();
                        $dimensions = \App\Models\Dimension::all();
                        $controls = \App\Models\Control::all();
                        $deadline = now()->addMonth()->format('Y-m-d');
                        $positions = \App\Models\Position::all();
                        $tasks = \App\Models\Task::where('status', '!=', 'completed')->get();
                    @endphp
                   
                    @csrf <!-- Использование встроенного Blade-директивы для CSRF токена -->
                    <div class="form-group">
                        <label for="project">{{__('Project')}}</label>
                        <select class="form-control" id="project" name="project_id">
                            @foreach($projects as $project)
                                <option value="{{$project->id}}"
                                    @if(request('project_id') || session('project_id') == $project->id)
                                        selected
                                    @endif
                                >{{$project->name}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="stage">{{__('Stage')}}</label>
                        <select class="form-control" id="stage" name="stage_id">
                            @foreach($stages as $stage)
                                <option value="{{$stage->id}}"
                                    @if(session('stage_id') == $stage->id)
                                        selected
                                    @endif
                                >{{$stage->name}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="step">{{__('Step')}}</label>
                        <select class="form-control" id="step" name="step_id">
                            @foreach($steps as $step)
                                <option value="{{$step->id}}"
                                    @if(session('step_id') == $step->id)
                                        selected
                                    @endif
                                >{{$step->name}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="dimension">{{__('Dimension')}}</label>
                        <select class="form-control" id="dimension" name="dimension_id">
                            @foreach($dimensions as $dimension)
                                <option value="{{$dimension->id}}"
                                    @if(session('dimension_id') == $dimension->id)
                                        selected
                                    @endif
                                >{{$dimension->name}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="control">{{__('Control')}}</label>
                        <select class="form-control" id="control" name="control_id">
                            @foreach($controls as $control)
                                <option value="{{$control->id}}"
                                    @if(session('control_id') == $control->id)
                                        selected
                                    @endif
                                >{{$control->name}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group" style="display: none;">
                        <label for="dependent_task">{{__('Dependent Task')}}</label>
                        <select class="form-control" id="dependent_task" name="dependent_task_id">
                            @foreach($tasks as $task)
                                <option value="{{$task->id}}"
                                    @if(session('dependent_task_id') == $task->id)
                                        selected
                                    @endif
                                >{{$task->name}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="deadline">{{__('Deadline')}}</label>
                        <input type="date" class="form-control" id="deadline" name="deadline" 
                        value="{{ session('deadline') ?? request('deadline') ?? $deadline }}">
                    </div>
                    <div class="form-group">
                        <label for="position">{{__('Responsible Position')}}</label>
                        <select class="form-control" id="position" name="responsible_position_id">
                            @foreach($positions as $position)
                                <option value="{{$position->id}}"
                                    @if(session('responsible_position_id') == $position->id)
                                        selected
                                    @endif
                                >{{$position->name}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="name">{{__('Name')}}</label>
                        <input type="text" class="form-control" id="name" name="name"
                            value="{{ session('name') }}">
                    </div>
                    <button type="submit" class="btn btn-primary">{{__('Create')}}</button>
                </form>
            </div>
        </div>
    </div>
@endsection
