@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
            <h1>{{__('Buildings')}}</h1>
        </div>
        </div>  
        @forEach($buildings as $building)
        <div class="row">
            <div class="col-md-2">                
              <a class="btn btn-primary" href="{{ route('rooms.show', $building->id) }}">  {{$building->abv}}  </a>          
            </div>
            <div class="col-md-8">

                {{$building->name}}            
            </div>

        </div>
        @endForEach  
    </div>
@endsection