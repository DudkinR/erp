@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
            <h1>{{__('Tasks')}}</h1>
            <a  href="{{ route('tasks.index') }}"
                class="btn btn-primary text-right"
                >{{__('Back')}}
            </a>
            @if(Auth::user()->hasRole('moderator','admin','quality-engineer'))
                <a  href="{{ route('tasks.create') }}"
                    class="btn btn-warning text-right"
                 >{{__('Create New')}}
                </a>
            @endif
                <a  href="{{ route('tasks.show_today') }}"
                    class="btn btn-success left"
                 >{{__('today tasks')}}
                </a>
            </div>
        </div> 
        @php
        $projects = []; 
        foreach($tasks as $task){
            if(!in_array($task->project, $projects))
            $projects[] = $task->project;
        }
       
        foreach($problems as $problem){
            if(!in_array($problem->project, $projects))
            $projects[] = $problem->project;
        }
        @endphp
        @foreach($projects as $project)
            <div class="row bg-warning">
                <div class="col-md-12">
                    <h2>{{ $project->name }}</h2>
                </div>
            </div>
            @foreach($tasks as $task)
                @if($project != $task->project)
                    @continue
                @endif
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <h2>{{ $task->step->name }}</h2>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="card-body">
                                        <p>{!! nl2br(e($task->step->description)) !!}</p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="card-body">
                                        @if($task->images)
                                            @foreach($task->images as $image)
                                                <img src="{{ $image->path }}" class="img-fluid" alt="{{ $image->name }}">
                                            @endforeach
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer">
                                <a href="{{ route('tasks.show', $task) }}" class="btn btn-warning">{{ __('Execute') }}</a>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
            @if($problems->count() > 0)
            @foreach($problems as $problem)
                @if($project != $problem->project)
                    @continue
                @endif
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <h2>{{ $problem->step->name }}</h2>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="card-body">
                                        <p>{!! nl2br(e($problem->step->description)) !!}</p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="card-body">
                                        @if($problem->images)
                                            @foreach($problem->images as $image)
                                                <img src="{{ $image->path }}" class="img-fluid" alt="{{ $image->name }}">
                                            @endforeach
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer">
                                <a href="{{ route('problems.show', $problem) }}" class="btn btn-danger">{{ __('Problem') }}</a>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            @endif


        @endforeach
      

    </div>
@endsection