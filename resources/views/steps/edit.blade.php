@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
            <h1>{{__('step edit')}}</h1>
                <form method="POST" action="{{ route('steps.update',$goal) }}">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <input type="hidden" name="_method" value="PUT">
                    <div class="form-group row">
                        <label for="name" class="col-md-4 col-form-label text-md-right">{{__('Name')}}</label>
                        <div class="col-md-6">
                            <input id="name" type="text" class="form-control" name="name" value="{{ $step->name }}" required autofocus>
                        </div>
                    </div> 
                    <div class="form-group row">
                        <label for="description" class="col-md-4 col-form-label text-md-right">{{__('Description')}}</label>
                        <div class="col-md-6">
                            <input id="description" type="text" class="form-control" name="description" value="{{ $step->description }}" required autofocus>
                        </div>
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
                   
                    <button type="submit" class="btn btn-primary">{{__('Create')}}</button>
                </form>
            </div>
        </div>
    </div>
@endsection