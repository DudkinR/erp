@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h1>{{__('Position new')}}</h1>
                <form method="POST" action="{{ route('positions.store') }}">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <div class="form-group row">
                        <label for="name" class="col-sm-2 col-form-label">{{__('Name')}}</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="name" name="name" placeholder="{{__('Name')}}">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="description" class="col-sm-2 col-form-label">{{__('Description')}}</label>
                        <div class="col-sm-10">
                            <textarea rows=8  class="form-control" id="description" name="description" placeholder="{{__('Description')}}"></textarea>
                        </div>
                    </div>
                     <div class="form-group row">
                        <label for="start" class="col-sm-2 col-form-label">{{__('Start')}}</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="start" name="start" placeholder="{{__('Start')}}">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="data_start" class="col-sm-2 col-form-label">{{__('Data Start')}}</label>
                        <div class="col-sm-10">
                            <input type="date" class="form-control" id="data_start" name="data_start" placeholder="{{__('Data Start')}}">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="division_id">{{__('Division')}}</label>
                        <select class="form-control" id="division_id" name="division_id">
                            @foreach($divisions as $division)
                                <option value="{{ $division->id }}">{{ $division->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group row">
                        <label for="closed" class="col-sm-2 col-form-label">{{__('Closed')}}</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="closed" name="closed" placeholder="{{__('Closed')}}">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="data_closed" class="col-sm-2 col-form-label">{{__('Data Closed')}}</label>
                        <div class="col-sm-10">
                            <input type="date" class="form-control" id="data_closed" name="data_closed" placeholder="{{__('Data Closed')}}">
                        </div>
                    </div>                  

                    <button type="submit" class="btn btn-primary">{{__('Create')}}</button>
                </form>
            </div>
        </div>
    </div>
@endsection