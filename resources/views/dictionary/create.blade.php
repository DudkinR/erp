@extends('layouts.app')
@section('content')
    <div class="container">
               @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        @if(session('success'))
        <div class="alert alert-success">{{ __(session('success')) }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ __(session('error')) }}</div>
    @endif
        <div class="row">
            <div class="col-md-12">
                <h1>{{__('New word')}}</h1>
                <form method="POST" action="{{ route('dictionary.store') }}">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <button type="submit" class="btn btn-primary w-100">{{__('Create')}}</button>
                </form>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <form method="POST" action="{{ route('dictionary.store') }}">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <div class="form-group">
                        <label for="en">{{__('en')}}
                            <small> ({{__('may not need to be filled in')}})</small>
                        </label>
                        <input type="text" class="form-control" id="en" name="en">
                    </div>
                    <div class="form-group">
                        <label for="uk">{{__('uk')}}
                            <small> ({{__('may not need to be filled in')}})</small>
                        </label>
                        <input type="text" class="form-control" id="uk" name="uk">
                    </div>
                    <div class="form-group">
                        <label for="ru">{{__('ru')}}
                            <small> ({{__('may not need to be filled in')}})</small>
                        </label>
                        <input type="text" class="form-control" id="ru" name="ru">
                    </div>
                    <div class="form-group">
                        <label for="description">{{__('description')}}
                            <small> ({{__('may not need to be filled in')}})</small>
                        </label>
                        <input type="text" class="form-control" id="description" name="description">
                    </div>
                    <div class="form-group">
                        <label for="example">{{__('example')}}
                            <small> ({{__('may not need to be filled in')}})</small>
                        </label>
                        <input type="text" class="form-control" id="example" name="example">
                    </div>
                    <button type="submit" class="btn btn-primary w-100">{{__('Create')}}</button>
                </form>
    </div>
@endsection