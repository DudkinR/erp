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
        <div class="row justify-content-center my-4">
            <div class="col-md-8">
                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white">
                        <h1 class="h4 mb-0">{{ __('Welcome to PPAPP') }}</h1>
                    </div>
                    <div class="card-body">
                        <p class="lead">{{ __('You are logged in') }}</p>
        
                        @if (isset(Auth::user()->profile->fio))
                            <p><strong>{{ __('Your name is') }}:</strong> {{ Auth::user()->profile->fio }}</p>
                        @endif
        
                        @if (isset(Auth::user()->profile->positions))
                            <p><strong>{{ __('Your positions are') }}:</strong></p>
                            <ul class="list-group list-group-flush">
                                @foreach(Auth::user()->profile->positions as $position)
                                    <li class="list-group-item"> {{ $position->name }}</li>
                                @endforeach
                            </ul>
                        @endif  
                        @if (isset(Auth::user()->profile->phones))
                            <p><strong>{{ __('Your phones are') }}:</strong></p>
                            <ul class="list-group   list-group-flush">
                                @foreach(Auth::user()->profile->phones as $phone)
                                    <li class="list-group-item"> {{ $phone->phone }}</li>
                                @endforeach
                            </ul>
                        @endif
        
                        <p><strong>{{ __('Your email is') }}:</strong> {{ Auth::user()->email }}</p>
        
                        <!-- Edit profile button -->
                        <div class="text-center mt-3">
                            <a href="{{ route('profiles.edit', Auth::user()->id) }}" class="btn btn-outline-primary">{{ __('Edit profile') }}</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @if(Auth::user()->hasRole('user'))
    <div class="container">
        <div class="row justify-content-center my-4">
            <div class="col-md-3">
                <h1>{{__('Personal')}}</h1>
                <p>
                    <a href="{{route('personal.index')}}" class = "btn btn-primary">
                        {{__('Find personals')}}
                    </a>
                </p>
            </div>
        </div>
    </div>
    @endif
    @if(Auth::user()->hasRole('moderator'))

        
    <div class="container">
        @php        
        $projects =\App\Models\Project::orderby('priority')
        ->where('current_state','!=','Закритий')
   //  ->where('execution_period', '>=', now())
   //   -> where('date', '>=', now())
        ->get(); 
        @endphp
        <div class="row">
            @foreach($projects as $project)
            @php
            $class_current_state = "warning";
             if ($project->current_state == "Закритий") {
                $class_current_state = "light";
            }
            if ($project->current_state == "Очікується оплата (після відвантаження)") {
                $class_current_state = "success";
            }
            if ($project->current_state == "Готовий до закриття") {
                $class_current_state = "primary";
            }
            if ($project->current_state == "Готовий до забезпечення") {
                $class_current_state = "danger";
            }
            @endphp

                <div class="col-md-2 text-center border border-dark rounded bg-{{$class_current_state}}">
                    <h6
                    title = "{{ $project->number }} {{ $project->description }}"
                    >{{ $project->name }}</h6>
                    <p>
                    {{__('Execution period')}}: {{ $project->execution_period }}
                    </p>
                    <p>
                    date: {{ $project->date }}
                    </p>
                  
                    <a href="{{ route('projects.show', $project->id) }}" class="btn btn-{{ $class_current_state }} btn-sm"
                     title = "{{ $project->current_state }}"
                    >{{__('Show')}}</a>
                </div>
            @endforeach
        </div>
    </div>
    @endif
@endauth
@endsection

