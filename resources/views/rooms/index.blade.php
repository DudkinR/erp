@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
            <h1>{{__('rooms')}}</h1>
            @if(Auth::user()->hasRole('quality-engineer','admin'))
            
                <a class="text-right" href="{{ route('rooms.create') }}">{{__('rooms')}}</a>
            @endif</div>
        </div>    
    </div>
@endsection