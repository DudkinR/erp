@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h1>{{__('Stages')}}</h1>
                <form method="POST" action="{{ route('stages.store') }}">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <div class="form-group mb-2">
                        <label>{{__('Name')}}</label>
                        <input type="text" class="form-control" name="name" required>
                    </div>
                    <div class="form-group mb-2">
                        <label>{{__('Description')}}</label>
                        <textarea class="form-control" name="description" rows="5"></textarea>
                    </div>
                    <div class="form-group 
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <button type="submit" class="btn btn-primary">{{__('Create')}}</button>
                </form>
            </div>
        </div>
    </div>
@endsection