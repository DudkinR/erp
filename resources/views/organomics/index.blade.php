@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
            <h1>{{__('Organomics')}}</h1>
              
            </div>
        </div>
        @forEach($buildings as $building)
        <div class="row">
            <div class="col-md-2">                
                {{$building->abv}}            
            </div>
            <div class="col-md-8">

                {{$building->name}}            
            </div>
            <div class="col-md-2">
                <a class="btn btn-primary" href="{{ route('organomic.show', $building->id) }}">{{__('Show')}}</a>
            </div>
        </div>
        @endForEach


    </div>
    <script>
   
    </script>
    
@endsection