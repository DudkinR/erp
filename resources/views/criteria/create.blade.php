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
                <h1>{{__('Criteria')}}</h1>
                <form method="POST" action="{{ route('criteria.store') }}">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <div class="form-group">
                        <label for="title">{{__('Name')}}</label>
                        <input type="text" class="form-control" id="name" name="name">
                    </div>
                    <div class="form-group">
                        <label for="description">{{__('Description')}}</label>
                        <textarea class="form-control" id="description" name="description" rows="10" ></textarea>
                    </div>
                    <div class="form-group">
                        <label for="weight">{{__('Weight')}}</label>
                        <input type="number" class="form-control" id="weight" name="weight" value="1">
                    </div>
                    <button type="submit" class="btn btn-primary">{{__('Create')}}</button>
                </form>
            </div>
        </div>
    </div>
@endsection