@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h1>{{__('Control add')}}</h1>
                <form method="POST" action="{{ route('controls.store') }}">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <div class="form-group  mb-2">
                        <label for="name">{{__('Control')}}</label>
                        <input type="text" class="form-control" id="namme" name="name" value="{{ old('name') }}">
                    </div>
                    <div class="form-group mb-2">
                        <label for="description">{{__('Description')}}</label>
                        <textarea class="form-control" rows=5 id="description" name="description">{{ old('description') }}</textarea>
                    </div>
                    <div class="form-group mb-2">
                        <?php $steps = App\Models\Step::orderBy('id', 'desc')->get(); ?>
                        <label for="steps_id">{{__('Steps')}}</label>
                        <select name="steps_id[]" id="steps_id" class="form-control" multiple>
                            @foreach($steps as $step)
                                <option value="{{ $step->id }}">{{ $step->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group mb-2">
                        <?php $dimensions = App\Models\Dimension::orderBy('id', 'desc')->get(); ?>
                        <label for="dimensions_id">{{__('Dimensions')}}</label>
                        <select name="dimensions_id[]" id="dimensions_id" class="form-control" multiple>
                            @foreach($dimensions as $dimension)
                                <option value="{{ $dimension->id }}">{{ $dimension->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary">{{__('Create')}}</button>
                </form>
            </div>
        </div>
    </div>
@endsection