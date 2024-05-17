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
               ->with ('project')
                ->with ('stage')
                ->with ('step')
                ->with('dimensions')
               ->get();
                @endphp
                <div class="row" id="ShowTask">
                    @foreach($tasks as $task)
                        <div class="col-md-4">
                            <div class="card">
                                <div class="card-header">
                                    <h2>{{__('Project')}} {{
                                        \App\Models\Project::find($task->project_id)->name
                                        }}</h2>
                                        <h3> {{__('Stage')}}
                                            {{
                                               \App\Models\Stage::find($task->stage_id)->name
                                            }}
                                        </h3>
                                        <h4>
                                            {{__('Step')}}
                                            {{
                                                \App\Models\Step::find($task->step_id)->name
                                            }}
                                        </h4>

                                </div>
                                <div class="card-body">
                                     <p>{{__('ID')}}: {{$task->id}}</p>
                                    <p>{{__('Dimension')}}: 
                                        <ul>
                                            @foreach($task->dimensions as $dimension)
                                                <li>
                                                ->withPivot('value', 'fact', 'status', 'comment', 'personal_id')
                                                {{$dimension->pivot->value}}
                                                     {{$dimension->name}}
                                                     ({{$dimension->pivot->fact}} - 
                                                     {{$dimension->pivot->status}} - 
                                                     {{$dimension->pivot->comment}}   - 
                                                     
                                                     {{$dimension->pivot->personal_id}}
                                                     )

                                                </li>
                                            @endforeach
                                        </ul>
                                        
                                    </p>


                                    <p>{{__('Status')}}: {{$task->status}}</p>
                                 
                                    <p>{{__('Control')}}: {{$task->control_id}}</p>
                                    <p>{{__('Deadline')}}: {{$task->deadline_date}}</p>
                                    <p>{{__('Responsible')}}: {{$task->responsible_position_id}}</p>
                                    <p>{{__('Dependent')}}: {{$task->dependent_task_id}}</p>
                                    <p>{{__('Parent')}}: {{$task->parent_task_id}}</p>
                                    <p>{{__('Real start')}}: {{$task->real_start_date}}</p>
                                    <p>{{__('Real end')}}: {{$task->real_end_date}}</p>

                                    <p>{{__('Created at')}}: {{$task->created_at}}</p>
                                    <p>{{__('Updated at')}}: {{$task->updated_at}}</p>
                                </div>
                                <div class="card-footer">
                                    <a href="{{route('tasks.show', $task->id)}}">{{__('Show')}}</a>
                                </div>
                            </div>
                        </div>
                    @endforeach

                </div>
                
            </div>
        </div>
    </div>
@endauth
@endsection

