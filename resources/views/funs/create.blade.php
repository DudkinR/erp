@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h1>{{__('New Function')}}</h1>
                <form method="POST" action="{{ route('funs.store') }}">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <div class="form-group">
                        <label for="name"> {{__('Function')}
                            Name</label>
                        <input type="text" class="form-control" id="name" name="name">
                    </div>
                    <div class="form-group">
                        <label for="description">Description</label>
                        <textarea class="form-control" id="description" name="description"></textarea>
                    </div>
                    <div class="form-group">
                        <label for="goals">Goals</label>
                        <select class="form-control" id="goals" name="goals[]" multiple size = 5>
                            @foreach($goals as $goal)
                                <option value="{{ $goal->id }}" 
                                @if($gl==$goal->id) 
                                    selected
                                @endif
                                >{{ $goal->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary">Create</button>
                </form>
            </div>
        </div>
    </div>
@endsection
