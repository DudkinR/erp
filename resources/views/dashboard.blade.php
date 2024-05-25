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
                <p>
                    {{__('Your id is')}}:
                    {{Auth::user()->id}}  
                  </p>
                  <p>
                    {{__('Your id is (personal)')}}:
                  </p>
                @if (isset(Auth::user()->profile->fio))
                <p>{{__('Your name is')}}: {{ Auth::user()->profile->fio}}</p>


                @endif
                @if (isset(Auth::user()->profile->positions))
                <p>{{__('Your positions are')}}: 
                    <ul>
                        @foreach(Auth::user()->profile->positions as $position)
                            <li>{{ $position->id }} {{ $position->name }}</li>
                        @endforeach
                    </ul>
                        @endif  
                <p>{{__('Your email is')}}: {{ Auth::user()->email }}</p>

                <!-- edit profile -->
                <a href="{{ route('profiles.edit', Auth::user()->id) }}" class="btn btn-primary">{{__('Edit profile')}}</a>
                
            </div>
        </div>
    </div>
@endauth
@endsection

