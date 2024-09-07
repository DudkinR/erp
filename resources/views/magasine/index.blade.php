@extends('layouts.app')
@section('content')
    <div class="container">
        @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif
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
        <div class="row">
            <div class="col-md-12">
                <a class="btn btn-warning w-100" href="{{ route('mag.create') }}">{{__('New Magazine')}}</a>
            </div>
        </div>
        @endif
        
        <div class="row">
            <div class="col-md-12">
                <a class="btn btn-success w-100" href="{{ route('mag.index') }}">{{__('All Magazines')}}</a>
            </div>
        </div>
    </div>
@endsection