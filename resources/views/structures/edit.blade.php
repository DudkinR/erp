@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h1>
                    {{__('Structure')}}:
                    {{ $structure->name }}
                </h1>
                <a class="text-right" href="{{ route('structure.index') }}">{{__('Structures')}}</a>
            </div>
        </div>
        <form method="POST" action="{{ route('structure.update', $structure->id) }}">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <input type="hidden" name="_method" value="PUT">
            <div class="form-group">
                <label for="name">{{__('Name')}}</label>
                <input type="text" class="form-control" id="name" name="name" value="{{ $structure->name }}">
            </div>
            <div class="form-group">
                <label for="description">{{__('Description')}}</label>
                <textarea class="form-control" id="description" name="description">{{ $structure->description }}</textarea>
            </div>
            <div class="form-group">
                <label for="abv">{{__('Abbreviation')}}</label>
                <input type="text" class="form-control" id="abv" name="abv" value="{{ $structure->abv }}">
            </div>
            <div class="form-group">
                <label for="slug">{{__('Slug')}}</label>
                <input type="text" class="form-control" id="slug" name="slug" value="{{ $structure->slug }}">
            </div>
            <div class="form-group">
                <label for="kod">
                    {{__('Kod in accounting')}}
                </label>
                <input type="text" class="form-control" value="{{ $structure->kod }}" id="kod" name="kod">
            </div>
            <div class="form-group">
                <label for="parent_id">{{__('Parent')}}</label>
                <select class="form-control" id="parent_id" name="parent_id">
                    <option value="0"></option>
                    @foreach($parents as $parent)
                        <option value="{{ $parent->id }}" @if($structure->parent_id == $parent->id) selected @endif>{{ $parent->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label for="positions">{{__('Positions')}}</label>
                <select class="form-control" id="positions" name="positions[]" multiple>
                    @foreach($positions as $position)
                        <option value="{{ $position->id }}" @if(in_array($position->id, $structure->positions->pluck('id')->toArray())) selected @endif>{{ $position->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label for="division_id">
                    {{__('Division')}}
                </label>                        
                <select class="form-control" id="division_id" name="division_id">
                    <option value="0">{{__('Select division')}}</option>
                    @foreach($divisions as $division)
                        <option value="{{$division->id}}">{{$division->name}}</option>
                    @endforeach
                </select>
            </div>
           
        <div class="form-group">
            <label for="status">
                {{__('Status')}}
            </label>
            <select class="form-control" id="status" name="status">
                <option value="active">{{__('Active')}}</option>
                <option value="inactive">{{__('Inactive')}}</option>
                <option value="deleted">{{__('Deleted')}}</option>
                <option value="draft">{{__('Draft')}}</option>

            </select>
        </div>
            <button type="submit" class="btn btn-primary">{{__('Update')}}</button>
        </form>
    </div>      
        
    </div>

@endsection

                