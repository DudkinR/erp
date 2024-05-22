@extends('layouts.app')
@section('content')
@guest
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h1>{{__('Welcome to PPAPP')}}</h1>
                <p>{{__('Please log in or register to continue')}}</p>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <h2>{{__('Log in')}}</h2>
                <form method="POST" action="{{ route('login') }}">
                    @csrf
                    <div class="form-group">
                        <label for="email">{{__('Email')}}</label>
                        <input type="email" name="email" id="email" class="form-control" required autofocus>
                        @error('email')
                            <div class="alert alert-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="password">{{__('Password')}}</label>
                        <input type="password" name="password" id="password" class="form-control" required>
                        @error('password')
                            <div class="alert alert-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <input type="checkbox" name="remember" id="remember" class="form-check-input">
                        <label for="remember" class="form-check-label">{{__('Remember me')}}</label>
                    </div>
                    <button type="submit" class="btn btn-primary">{{__('Log in')}}</button>
                </form>
            </div>
        </div>
    </div>
@endguest
@auth
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h1>{{__('Welcome to PPAPP')}}</h1>
                <p>{{__('You are logged in')}}</p>
                @php 
                    $tasks = \App\Models\Task::where('status', 'active')
                        ->with('project')
                        ->with('stage')
                        ->with('step')
                        ->with('dimensions')
                        ->get();
                @endphp
                <div class="row" id="ShowTask">
                    @if($tasks->isEmpty())
                        <div class="col-md-12">
                            <p>{{__('No tasks found')}}</p>
                        </div>
                    @else
                        @foreach($tasks as $task)
                            @php
                                $project = $task->project;
                                $stage = $task->stage;
                                $step = $task->step;
                            @endphp
                            <div class="col-md-4">
                                <div class="card">
                                    <div class="card-header">
                                        <h2>{{__('Project')}} {{ $project ? $project->name : __('N/A') }}</h2>
                                        <h3>{{__('Stage')}} {{ $stage ? $stage->name : __('N/A') }}</h3>
                                        <h4>{{__('Step')}} {{ $step ? $step->name : __('N/A') }}</h4>
                                    </div>
                                    <div class="card-body">
                                        <p>{{__('Status')}}: {{$task->status}}</p>                                 
                                    </div>
                                    <div class="card-footer">
                                        <a href="{{route('tasks.show', $task->id)}}">{{__('Show')}}</a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>
        </div>
    </div>
@endauth
@endsection
