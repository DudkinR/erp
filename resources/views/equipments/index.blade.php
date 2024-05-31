@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
            <h1>{{__('equipments')}}</h1>
                <a class="text-right" href="{{ route('equipments.create') }}">{{__('equipments')}}</a>
            </div>
        </div>    
    </div>
@endsection