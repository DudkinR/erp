@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
            <h1>{{__('Magasines')}}</h1>
                <a class="text-right" href="{{ route('magasines.create') }}">{{__('Create')}}</a>
            </div>
        </div>    
    </div>
@endsection