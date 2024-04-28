@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
            <h1>{{__('Stages')}}</h1>
                <form method="POST" action="{{ route('stages.update',$stage) }}">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <input type="hidden" name="_method" value="PUT">
                    <div class="form-group mb-2">
                        <label>{{__('Name')}}</label>
                        <input type="text" class="form-control" name="name" value="{{ $stage->name }}" required>
                    </div>
                    <div class="form-group mb-2">
                        <label>{{__('Description')}}</label>
                        <textarea class="form-control" name="description" rows="5">{{ $stage->description }}</textarea>
                    </div>
                    
                   
                    <button type="submit" class="btn btn-primary">{{__('Create')}}</button>
                </form>
            </div>
        </div>
    </div>
@endsection