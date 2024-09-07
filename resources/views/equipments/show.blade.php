@extends('layouts.app')
@section('content')
    <div class="container">
        @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif
        <div class="row">
            <div class="col-md-12">
            <h1>{{__('equipments')}}</h1>
                <a class="text-right
                " href="{{ route('equipments.index') }}">Back</a>
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