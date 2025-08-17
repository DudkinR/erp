@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
            <h1>{{__('stores')}}</h1>
            @if(Auth::user()->hasRole('admin'))
            
                <a class="text-right" href="{{ route('stores.create') }}">{{__('stores')}}</a>
           @endif </div>
        </div>    
    </div>
@endsection