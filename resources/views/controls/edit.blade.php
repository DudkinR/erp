@extends('layouts.app')
@section('content')
    <div class="container">
        @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif
        <div class="row">
            <div class="col-md-12">
            <h1>{{__('controls')}}</h1>
                <form method="POST" action="{{ route('controls.update',$control) }}">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <input type="hidden" name="_method" value="PUT">
                    <div class="form-group mb-2">
                        <label for="control">{{__('Control')}}</label>
                        <input type="text" class="form-control" id="name" name="name" value="{{ $control->name }}">
                    </div>
                    <div class="form-group mb-2">
                        <label for="description">{{__('Description')}}</label>
                        <textarea class="form-control" rows=5 id="description" name="description">{{ $control->description }}</textarea>
                    </div>
                    <div class="form-group mb-2">
                        <?php $steps = App\Models\Step::orderBy('id', 'desc')->get(); ?>
                        <label for="steps_id">{{__('Steps')}}</label>
                        <select name="steps_id[]" id="steps_id" class="form-control" multiple>
                            @foreach($steps as $step)
                                <option value="{{ $step->id }}" @if(in_array($step->id, $control->steps->pluck('id')->toArray())) selected @endif>{{ $step->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <?php $dimensions = App\Models\Dimension::orderBy('id', 'desc')->get(); ?>
                    <div class="form-group mb-2">
                        <label for="dimensions_id">{{__('Dimensions')}}</label>
                        <select name="dimensions_id[]" id="dimensions_id" class="form-control" multiple>
                            @foreach($dimensions as $dimension)
                                <option value="{{ $dimension->id }}" @if(in_array($dimension->id, $control->dimensions->pluck('id')->toArray())) selected @endif>{{ $dimension->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group mb-2">
                        <label for="dimensions_new">{{__('New Dimension')}}</label>
                        <input type="text" class="form-control" id="dimensions_new" name="dimensions_new">
                        <button type="button" class="btn btn-primary" onclick="add_dimension_to_control({{ $control->id }})">{{__('Add')}}</button>
                    </div>
                    
                    <button type="submit" class="btn btn-primary">{{__('Update')}}</button>

                </form>
            </div>
        </div>
    </div>
@endsection