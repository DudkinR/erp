@extends('layouts.app')
@section('content')
    <div class="container">

        <div class="row">
            <div class="col-md-12">
            <h1>{{__('_______')}}</h1>
                <a class="btn btn-light w-100" href="{{ route('_______.create') }}">{{__('Create')}}</a>
            </div>
        </div>    
    </div>
@endsection