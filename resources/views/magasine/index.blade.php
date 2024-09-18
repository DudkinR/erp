@extends('layouts.app')
@section('content')
    <div class="container">
               @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        @if(session('success'))
        <div class="alert alert-success">{{ __(session('success')) }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ __(session('error')) }}</div>
    @endif
        <div class="row">
            <div class="col-md-12">
            <h1>{{__('Magasines')}}</h1>              
            </div>
        </div> 
        @if(Auth::user()->hasRole('moderator','admin','quality-engineer','workshop-chief','department-chief'))
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