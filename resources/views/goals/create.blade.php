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
                <h1>{{__('Create Goal')}}</h1>
                <form method="POST" action="{{ route('goals.store') }}">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <div class="form-group">
                        <label for="title">{{__('Title')}}</label>
                        <input type="text" class="form-control" id="name" name="name">
                    </div>
                    <div class="form-group">
                        <label for="description">{{__('Description')}}</label>
                        <textarea class="form-control" rows=10 id="description" name="description"></textarea>
                    </div>
                    <div class="form-group">
                        <label for="due_date">{{__('Due Date')}}</label>
                        <input type="date" value="{{ date('Y-m-d', strtotime('+1 year')) }}" class="form-control" id="due_date" name="due_date">
                    </div>
                    <button type="submit" class="btn btn-primary">{{__('Create')}}</button>
                </form>
            </div>
        </div>
    </div>
@endsection