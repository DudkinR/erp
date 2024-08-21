@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
            <h1>{{__('Master')}}</h1>
            </div>
        </div> 
        <div class="row">
            <div class="col-md-1">№</div>
            <div class="col-md-3">
                {{__('Task')}}
            </div>
            <div class="col-md-3">
                {{__('Termin')}}
            </div>
            <div class="col-md-2">
                {{__('Start')}}
            </div>
            <div class="col-md-2">
                {{__('Finish')}}
            </div>
        </div>  
        <div class="row">
            <a class="text-right" href="{{ route('master.create') }}">{{__('New')}}</a>
        </div>       
        @foreach ($masters as $item)
        <div class="row">
            <div class="col-md-1">{{ $index + 1 }}</div>
            <div class="col-md-3">
                <a href="">
                {{ $item->task }}</a>
            </div>
            <div class="col-md-3">{{ $item->termin }}</div>
            <div class="col-md-2">{{ $item->start }}</div>
            <div class="col-md-2">{{ $item->finish }}</div>
        </div>
            
        @endforeach 
          
        <div class="row">
            <a class="text-right" href="{{ route('master.create') }}">{{__('New')}}</a>
        </div>
    </div>
@endsection