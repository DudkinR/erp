@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h1>{{__('Master')}}</h1>
                <form method="POST" action="{{ route('master.store') }}">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                      <div class="form-group" style="margin-top: 20px;">
                        <label for="task">{{__('Task')}}</label>
                        <input type="text" class="form-control" id="task" name="task" value="{{ old('task') }}">
                    </div>
                    <div class="form-group">
                        <label for="urgency">{{__('Urgency')}}</label>
                        <input type="number" class="form-control" id="urgency" name="urgency" value="{{ old('urgency') }}" min="1" max="10">
                    </div>
                        <div class="form-group">
                        <label for="deadline">{{__('Deadline')}}</label>
                        <input type="text" class="form-control" id="deadline" name="deadline" value="{{ old('deadline') }}">
                    </div>
                  
                    <button type="submit" class="btn btn-primary">{{__('Create')}}</button>
                </form>
            </div>
        </div>
    </div>
@endsection