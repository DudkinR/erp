@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h1>{{__('Objective')}}</h1>
                <form method="POST" action="{{ route('objectives.store') }}">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <div class="form-group">
                        <label for="name">{{__('Name')}}</label>
                        <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}">
                    </div>
                    <div class="form-group">
                        <label for="description">{{__('Description')}}</label>
                        <textarea class="form-control" id="description" rows="6" name="description">{{ old('description') }}</textarea>
                    </div>
                    <div class="form-group">
                        <label for="goals">{{__('Goals')}}</label>
                        @php 
                            $goal_id = Request::get('goal_id');
                        @endphp
                        <select class="form-control" id="goals" name="goals[]" multiple>
                            @foreach($goals as $goal)
                                <option value="{{ $goal->id }}"
                                    @if($goal->id == $goal_id)
                                        selected
                                    @endif
                                    >{{ $goal->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="functs">{{__('Functs')}}</label>
                        <select class="form-control" id="functs" name="functs[]" multiple>
                            @foreach($functs as $funct)
                                <option value="{{ $funct->id }}">{{ $funct->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary">{{__('Create')}}</button>
                </form>
            </div>
        </div>
    </div>
@endsection