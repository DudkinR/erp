@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
            <h1>{{__('Position')}}</h1>
                <form method="POST" action="{{ route('positions.update',$position) }}">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <input type="hidden" name="_method" value="PUT">
                    <div class="form-group">
                        <label for="name">{{__('Name')}}</label>
                        <input type="text" class="form-control" id="name" name="name" value="{{$position->name}}">
                    </div>
                    <div class="form-group">
                        <label for="description">{{__('Description')}}</label>
                        <textarea rows=8  class="form-control" id="description" name="description">{{$position->description}}</textarea>
                    </div>
                    <div class="form-group">
                        <label for="start">{{__('Start')}}</label>
                        <input type="text" class="form-control" id="start" name="start" value="{{$position->start}}">
                    </div>
                    <div class="form-group">
                        <label for="data_start">{{__('Data Start')}}</label>
                        <input type="date" class="form-control" id="data_start" name="data_start" value="{{$position->data_start}}">
                    </div>
                    <div class="form-group">
                        <label for="closed">{{__('Closed')}}</label>
                        <input type="text" class="form-control" id="closed" name="closed" value="{{$position->closed}}">
                    </div>
                    <div class="form-group">
                        <label for="data_closed">{{__('Data Closed')}}</label>
                        <input type="date" class="form-control" id="data_closed" name="data_closed" value="{{$position->data_closed}}">
                    </div>
                    <div class="form-group">
                        <label for="position_id">{{__('Position')}}</label>
                        <?php $positions = App\Models\Position::orderBy('id', 'desc')->get(); ?>
                        <select class="form-control" id="position_id" name="position_id">
                            @foreach($positions as $position)
                                <option value="{{$position->id}}">{{$position->name}}</option>
                            @endforeach
                        </select>
                    </div>                   
                    <button type="submit" class="btn btn-primary">{{__('Update')}}</button>
                </form>
            </div>
        </div>
    </div>
@endsection