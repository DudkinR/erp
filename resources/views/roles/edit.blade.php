@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
            <h1>{{__('Role')}}</h1>
                <a class="text-right" href="{{ route('roles.index') }}">{{__('Back')}}</a>
                <form method="POST" action="{{ route('roles.update',$role) }}">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <input type="hidden" name="_method" value="PUT">
                    <div class="form-group">
                        <label for="name">{{__('Name')}}</label>
                        <input type="text" class="form-control" id="name" name="name" value="{{ $role->name }}">
                    </div>
                    <div class="form-group">
                        <label for="description">{{__('Description')}}</label>
                        <textarea class="form-control" id="description" name="description">{{ $role->description }}</textarea>                   
                    </div>
                    <div class="form-group">
                        <label for="slug">{{__('Slug')}}</label>
                        <input type="text" class="form-control" id="slug" name="slug" value="{{ $role->slug }}">
                    </div>                    
                    <button type="submit" class="btn btn-primary">{{__('Update')}}</button>
                </form>
            </div>
        </div>
    </div>
@endsection