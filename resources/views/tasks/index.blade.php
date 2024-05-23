@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
            <h1>{{__('Tasks')}}</h1>
                <a class="text-right" href="{{ route('tasks.create') }}">{{__('Create')}}</a>
            </div>
        </div> 
        @php $project = null; @endphp
        @foreach($tasks as $task)
            @if($project != $task->project)
                @php $project = $task->project; @endphp
                <div class="row bg-warning">
                    <div class="col-md-12">
                        <h2>{{ $project->name }}</h2>
                    </div>
                </div>
            @endif
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h2>{{ $task->step->name }}</h2>
                        </div>
                        <div class="card-body">
                            <p>{!! nl2br(e($task->step->description)) !!}</p>
                        </div>
                        <div class="card-footer">
                            <a href="{{ route('tasks.show', $task) }}" class="btn btn-warning">{{ __('Execute') }}</a>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach   
    </div>
@endsection