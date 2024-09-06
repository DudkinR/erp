@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
            <h1>{{__('Magasines')}}</h1>              
            </div>
        </div> 
        @if(Auth::user()->hasRole('moderator','admin','quality-engineer','workshop-chief'))
        <div class="row">
            <div class="col-md-12">
<a class="btn btn-success w-100" href="{{ route('master.index') }}">{{__('Tasks magazine')}}</a>
            </div>
        </div>
        @endif
    </div>
@endsection