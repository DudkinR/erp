@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
            <h1>{{__('Division')}}</h1>
                <form method="POST" action="{{ route('divisions.update',$division) }}">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <input type="hidden" name="_method" value="PUT">
                    <div class="form-group">
                        <label for="name">{{__('Name')}}</label>
                        <input type="text" class="form-control" id="name" name="name" value="{{ $division->name }}">
                    </div>
                    <div class="form-group">
                        <label for="description">{{__('Description')}}</label>
                        <textarea class="form-control" id="description" name="description">{{ $division->description }}</textarea>
                    </div>
                    <div class="form-group">
                        <label for="abv">{{__('Abbreviation')}}</label>
                        <input type="text" class="form-control" id="abv" name="abv" value="{{ $division->abv }}">
                    </div>                   
                    <div class="form-group">
                        <label for="slug">{{__('Slug')}}</label>
                        <input type="text" class="form-control" id="slug" name="slug" value="{{ $division->slug }}">
                    </div>
                    <div class="form-group">
                        <label for="parent_id">{{__('Parent')}}</label>
                        <select class="form-control" id="parent_id" name="parent_id">
                            <option value=""></option>
                            @foreach($parents as $parent)
                                <option value="{{ $parent->id }}" @if($division->parent_id == $parent->id) selected @endif>{{ $parent->name }}</option>  
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="positions">{{__('Positions')}}</label>
                        <select class="form-control" id="positions" name="positions[]" multiple>
                            @foreach($positions as $position)
                                <option value="{{ $position->id }}" @if(in_array($position->id, $division->positions->pluck('id')->toArray())) selected @endif>{{ $position->name }}</option>  
                            @endforeach
                        </select>
                    </div>

                    <button type="submit" class="btn btn-primary">{{__('Update')}}</button>
                </form>
            </div>
        </div>
    </div>
@endsection