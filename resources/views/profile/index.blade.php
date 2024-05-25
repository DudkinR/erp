@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
            <h1>{{__('Profile')}}</h1>
            </div>
        </div>    
        <div class="row">
            <div class="col-md-12"> 
                {{__('Email')}}: {{$user->email}}
                <br>
                @if(isset($user->profile->fio))
                {{__('fio')}} : {{$user->profile->fio}}
                @endif
                <br>
                @if (isset($user->profile->positions))
                {{__('Positions')}} : 
                <ul>
                    @foreach($user->profile->positions as $position)
                        <li>{{$position->name}}</li>
                    @endforeach
                </ul>
                <br>
                @endif
                @if (isset($user->roles))
                {{__('Roles')}} :
                <ul>
                    @foreach($user->roles as $role)
                        <li>{{$role->name}}</li>
                    @endforeach
                </ul>
                <br>
                @endif
                 hasRoles('admin') : {{ $user->hasRole('admin') ? 'true' : 'false' }}
                <br>
                hasRoles('user') : {{ $user->hasRole('user') ? 'true' : 'false' }}
                <br>
                hasRoles('quality-engineer') : {{ $user->hasRole('quality-engineer') ? 'true' : 'false' }}
                <br>
                hasRoles('admin', 'user') : {{ $user->hasRole('admin', 'user') ? 'true' : 'false' }}
        </div>
    </div>
@endsection