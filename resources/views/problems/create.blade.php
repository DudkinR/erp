@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h1>{{__('New problem')}}</h1>
                <form method="POST" action="{{ route('problems.store') }}">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <?php  
                        $projectsList = \App\Models\Project::where('current_state', '<>', 'Закритий')->get(); 
                    ?>

                    <div class="form-group mb-2">
                        <label for="project">{{__('Project')}}  {{ $project_id }}</label>
                        <select name="project" id="project" class="form-control">
                            <option value="0">{{__('New project')}}</option>
                            @foreach($projectsList as $projectItem)
                                <option value="{{ $projectItem->id }}" {{ $project_id == $projectItem->id ? 'selected' : '' }}>
                                    {{ $projectItem->name }} 
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <?php  $stage = \App\Models\Stage::all();                  
                    ?>
                    <div class="form-group mb-2">
                        <label for="stage">{{__('Stage')}}</label>
                        <select name="stage" id="stage" class="form-control">
                            <option value="0">{{__('New stage')}}</option>
                            @foreach($stage as $stage)
                                <option value="{{ $stage->id }}"
                                @if($stage_id && $stage_id == $stage->id)
                                    selected
                                @endif
                                >{{ $stage->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <?php  $step = \App\Models\Step::all(); ?>
                    <div class="form-group mb-2">
                        <label for="step">{{__('Step')}}</label>
                        <select name="step" id="step" class="form-control">
                            <option value="0">{{__('New step')}}</option>
                            @foreach($step as $step)
                                <option value="{{ $step->id }}"
                                @if($step_id && $step_id == $step->id)
                                    selected
                                @endif
                                >{{ $step->name }}</option>
                            @endforeach
                        </select>

                    </div>
                    <?php  $control = \App\Models\Control::all(); ?>
                    <div class="form-group mb-2">
                        <label for="control">{{__('Control')}}</label>
                        <select name="control" id="control" class="form-control">
                            <option value="0">{{__('New control')}}</option>
                            @foreach($control as $control)
                                <option value="{{ $control->id }}"
                                @if($control_id && $control_id == $control->id)
                                    selected
                                @endif
                                >{{ $control->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group mb-2 bg-info">
                        <label for="name"><b>{{__('Name')}}</b></label>
                        <input type="text" class="form-control" id="name" name="name" >
                    </div>
                    <div class="form-group mb-2  bg-warning">
                        <label for="description">{{__('Description')}}</label>
                        <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                    </div>
                    <div class="form-group mb-2">
                        <label for="priority">{{__('Priority')}}</label>
                        <input type="number" class="form-control" id="priority" name="priority" value="0" >
                    </div>
                    <div class="form-group mb-2">
                        <label for="date_start">{{__('Date start')}}</label>
                        <input type="date" class="form-control" id="date_start" name="date_start" value="{{ date('Y-m-d') }}">
                    </div>
                    <div class="form-group mb-2">
                        <label for="date_end">{{__('Date end')}}</label>
                        <input type="date" class="form-control" id="date_end" name="date_end" >
                    </div>
                    <div class="form-group mb-2">
                        <label for="deadline">{{__('Deadline')}}</label>
                        <input type="date" class="form-control" id="deadline" name="deadline" >
                    </div>
                    <div class="form-group mb-2">
                        <label for="status">{{__('Status')}}</label>
                        <select name="status" id="status" class="form-control">
                            <option value="new">{{__('New')}}</option>
                            <option value="in_progress">{{__('In progress')}}</option>
                            <option value="done">{{__('Done')}}</option>
                            <option value="closed">{{__('Closed')}}</option>
                        </select>
                    </div>
                    <?php
                     $personals = \App\Models\Personal::where('status',  'Робота')->get();
                     ?>
                    <div class="form-group mb-2">
                        <label for="personal">{{__('Personal')}}</label>
                        <select name="personal" id="personal" class="form-control">
                            <option value="0">{{__('Не призначен')}}</option>
                            @foreach($personals as $personal)
                                <option value="{{ $personal->id }}"
                                @if($personal_id && $personal_id == $personal->id)
                                    selected
                                @endif
                                >{{ $personal->nickname }}</option>
                            @endforeach
                        </select>
                    </div>
                 <button type="submit" class="btn btn-primary">{{__('Create')}}</button>
                </form>
            </div>
        </div>
    </div>
@endsection