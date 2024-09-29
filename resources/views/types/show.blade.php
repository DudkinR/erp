@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
            <h1>{{__('Type')}}</h1>
                <a class="text-right
                " href="{{ route('types.index') }}">Back</a>
            </div>  
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                <div class="card-header">
                    {{ $type->name }}
                </div>
                <div class="card-body">
                    <p>{{ $type->description }}</p>
                </div>
                
               </div>
            </div>
        </div>
   </div>
@endsection