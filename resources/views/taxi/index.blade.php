@extends('layouts.app')
@section('content')
    <div class="container">

        <div class="row">
            <div class="col-md-12">
            <h1>{{__('Words')}}</h1>
                <a class="btn btn-light w-100" href="{{ route('words.create') }}">{{__('Create')}}</a>
            </div>
        </div>    
    </div>
@endsection