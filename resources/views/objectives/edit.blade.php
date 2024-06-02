@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
            <h1>{{__('Objective')}}</h1>
                <form method="POST" action="{{ route('objectives.update',$objective) }}">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <input type="hidden" name="_method" value="PUT">
                    <div class="form-group">
                        <label for="name">{{__('Name')}}</label>
                        <input type="text" class="form-control" id="name" name="name" value="{{ $objective->name }}">                   
                    </div>
                    <div class="form-group">
                        <label for="description">{{__('Description')}}</label>
                        <textarea class="form-control" id="description" rows="6" name="description">{{ $objective->description }}</textarea>
                    </div>
                    <div class="form-group">
                        <label for="goals">{{__('Goals')}}</label>
                        <select class="form-control" id="goals" name="goals[]" multiple>
                            @foreach($goals as $goal)
                                <option value="{{ $goal->id }}" @if(in_array($goal->id, $objective->goals->pluck('id')->toArray())) selected @endif>{{ $goal->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="functs">{{__('Functs')}}</label>
                        <select class="form-control" id="functs" name="functs[]" multiple>
                            @foreach($functs as $funct)
                                <option value="{{ $funct->id }}" @if(in_array($funct->id, $objective->functs->pluck('id')->toArray())) selected @endif>{{ $funct->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <a href="{{ route('objectives.show', $objective) }}" class="btn btn-secondary">{{__('Cancel')}}</a>
                    

                    <button type="submit" class="btn btn-primary">{{__('Update')}}</button>
                </form>
            </div>
        </div>
    </div>
@endsection