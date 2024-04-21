@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h1> {{__('Edit Goal')}}</h1>
                <form method="POST" action="{{ route('funs.update',$fun) }}">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <input type="hidden" name="_method" value="PUT">
                    <div class="form-group">
                        <label for="title"> {{__('Title')}}</label>
                        <input type="text" class="form-control" id="name" name="name" value="{{ $fun->name }}">
                    </div>
                    <div class="form-group">
                        <label for="description"> {{__('Description')}}</label>
                        <textarea class="form-control" id="description" name="description">{!! $fun->description !!} </textarea>
                    </div>
                    <div class="form-group">
                        <label for="goals"> {{__('Goals')}}</label>
                        <select class="form-control" id="goals" name="goals[]" multiple size = 5>
                            @foreach($goals as $goal)
                                <option value="{{ $goal->id }}" 
                                @if(in_array($goal->id, $fun->goals->pluck('id')->toArray())) 
                                    selected
                                @endif
                                >{{ $goal->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <a href="{{route('goals.create')}}" class="btn btn-info" >{{__('Add Goal')}}</a>
                        <hr>
                    </div>
                    <div class="form-group">
                        <label for="positions"> {{__('Positions')}}</label>
                        <select class="form-control" id = "positions" name="positions[]" multiple size = 5>
                            <option value="0">{{__('Not')}}</option>
                            @foreach($positions as $position)
                                <option value="{{ $position->id }}" 
                                @if(in_array($position->id, $fun->positions->pluck('id')->toArray())) 
                                    selected
                                @endif
                                >{{ $position->name }}</option>
                            @endforeach

                        </select>
                    </div>
                    <div class="form-group">
                        <a href="{{route('personal.create')}}" class="btn btn-info" >{{__('Add Position')}}</a>
                        <hr>
                    </div>
                    <button type="submit" class="btn btn-primary">
                        {{__('Update')}}
                    </button>
                </form>
            </div>
        </div>
    </div>
@endsection