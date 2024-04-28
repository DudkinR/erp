@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h1>{{__('Step new')}}</h1>
                <form method="POST" action="{{ route('steps.store') }}">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <div class="form-group">
                        <label for="name">{{__('Name')}}</label>
                        <input type="text" class="form-control" id="name" name="name">
                    </div>
                    <div class="form-group ">
                        <label for="description">{{__('Description')}}</label>
                        <textarea class="form-control" id="description" name="description" rows=5></textarea>
                    </div>
                    
                    <div class="form-group">
                        <label for="stage">{{__('Stage')}}</label>
                        <select class="form-control" id="stage" name="stage[]" multiple>
                            <option value="0">{{__('Empty')}}</option>
                            <?php $stages = App\Models\Stage::all(); ?>
                            @foreach($stages as $stage)
                                <option value="{{$stage->id}}"
                                    >{{$stage->name}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="control">{{__('Controls')}}</label>
                        <?php $controls = App\Models\Control::all(); ?>
                        <select class="form-control" id="control" name="control">
                            <option value="0">{{__('Empty')}}</option>
                            @foreach($controls as $control)
                                <option value="{{$control->id}}">{{$control->name}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group ">
                        <label for="add_new_control">{{__('Add new control Name')}}</label>
                        <input type="text" class="form-control" id="add_new_control_name" name="add_new_control_name">
                    </div>
                    <div class="form-group ">
                        <label for="add_new_control_description">{{__('Add new control Description')}}</label>
                        <textarea class="form-control" id="add_new_control_description" name="add_new_control_description" rows=5></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">{{__('Create')}}</button>
                </form>
            </div>
        </div>
    </div>
@endsection