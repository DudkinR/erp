@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
            <h1>{{__('Magasine')}}</h1>
                <a class="text-right
                " href="{{ route('magasines.index') }}">
                {{__('Back')}}</a>
            </div>  
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="card">
               </div>
            </div>
        </div>
   </div>
@endsection