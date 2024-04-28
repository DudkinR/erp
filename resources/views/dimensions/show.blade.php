@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
            <h1>{{__('Dimension')}}</h1>
                <a class="text-right
                " href="{{ route('dimensions.index') }}">{{__('Back')}}</a>
            </div>  
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        {{ $dimension->name }}
                    </div>
                    <div class="card-body">
                        <p>{{ $dimension->description }}</p>
                        <a href="{{ route('dimensions.edit',$dimension) }}">{{__('Edit')}}</a>
                    </div>
                    
               </div>
            </div>
        </div>
   </div>
@endsection