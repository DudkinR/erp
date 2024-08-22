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
            <div class="col-md-1">
                {{__('Termin')}}
            </div>
            <div class="col-md-2">
                {{__('Start')}}
            </div>
            <div class="col-md-2">
                {{__('Finish')}}
            </div>
            <div class="col-md-2">
                {{__('Action')}}
            </div>

        </div>  
        <div class="row">
            <a class="text-right" href="{{ route('master.create') }}">{{__('New')}}</a>
        </div>       
        @foreach ($masters as $item)
        <div class="row">
            <div class="col-md-1">{{$item->id}}</div>
            <div class="col-md-3">
                <a href="">
                {{ $item->text }}</a>
            </div>
            <div class="col-md-1">
                {{  $item->deadline }}
            </div>
            <div class="col-md-2">{{ $item->start }}</div>
            <div class="col-md-2">{{ $item->finish }}</div>
            <div class="col-md-2">
                <a class="btn btn-info" href="{{ route('master.step1', $item->id) }}">{{__('Next')}}</a>
            </div>

        </div>
            
        @endforeach 
          
        <div class="row">
            <a class="text-right" href="{{ route('master.create') }}">{{__('New')}}</a>
        </div>
    </div>
@endsection