@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
            <h1>{{__('Criteria')}}</h1>
                <a class="text-right" href="{{ route('criteria.create') }}">{{__('Create')}}</a>
            </div>
        </div>    
    </div>
@endsection