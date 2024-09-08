@extends('layouts.app')
@section('content')
    <div class="container">
        @if($errors->any())
            <div class="alert alert-danger" role="alert">
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
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
                <h1>{{__('Division')}}</h1>
                <form method="POST" action="{{ route('divisions.store') }}">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <div class="form-group">
                        <label for="name">{{__('Name')}}</label>
                        <input type="text" class="form-control" id="name" name="name">
                    </div>
                    <div class="form-group">
                        <label for="description">{{__('Description')}}</label>
                        <textarea class="form-control" id="description" name="description"></textarea>
                    </div>
                    <div class="form-group">
                        <label for="abv">{{__('Abbreviation')}}</label>
                        <input type="text" class="form-control" id="abv" name="abv">
                    </div>
                    <div class="form-group">
                        <label for="slug">{{__('Slug')}}</label>
                        <input type="text" class="form-control" id="slug" name="slug">
                    </div>                    
                    <div class="form-group">
                        <label for="parent_id">{{__('Parent')}}</label>
                        <select class="form-control" id="parent_id" name="parent_id">
                            <option value=""></option>
                            @foreach($parents as $parent)
                                <option value="{{ $parent->id }}">{{ $parent->name }}</option>  
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="positions">{{__('Positions')}}</label>
                        <select class="form-control" id="positions" name="positions[]" multiple>
                            @foreach($positions as $position)
                                <option value="{{ $position->id }}">{{ $position->name }}</option>  
                            @endforeach
                        </select>
                    </div>  
                    <button type="submit" class="btn btn-primary">{{__('Create')}}</button>
                </form>
            </div>
        </div>
    </div>
@endsection