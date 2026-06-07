@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h1>{{__('_______')}}</h1>
                <form method="POST" action="{{ route('_______.store') }}">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <button type="submit" class="btn btn-primary w-100">{{__('Create')}}</button>
                </form>
            </div>
        </div>
    </div>
@endsection