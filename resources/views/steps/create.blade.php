@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h1>{{__('Steps')}}</h1>
                @if(isset($step))
                <h2>{{__('Copy Step')}}</h2>
                @endif
                <form method="POST" action="{{ route('steps.store') }}">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <div class="form-group mb-2">
                        <label for="step">{{__('Step')}}</label>
                        <input type="text" class="form-control" id="name" name="name" value=" @if(isset($step)){{ $step->name }}@endif {{ old('name') }}">
                    </div>
                    <div class="form-group mb-2">
                        <label for="description">{{__('Description')}}</label>
                        <textarea class="form-control" rows=5 id="description" name="description">@if(isset($step)){{ $step->description }}@endif{{ old('description') }}</textarea>    
                    </div>

                    <div class="form-group mb-2">
                    <?php $controls = App\Models\Control::orderBy('id', 'desc')->get(); ?>
                        <label for="controls_id">{{__('Controls')}}</label>
                        <select name="controls_id[]" id="controls_id" class="form-control" multiple>
                            @foreach($controls as $control)
                                <option value="{{ $control->id }}"
                                    @if(isset($step) && $step->controls->contains($control->id)) selected @endif
                                    >{{ $control->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group mb-2">
                        <?php $stages = App\Models\Stage::orderBy('id', 'desc')->get(); ?>
                        <label for="stages_id">{{__('Stages')}}</label>
                        <select name="stages_id[]" id="stages_id" class="form-control" multiple>
                            @foreach($stages as $stage)
                                <option value="{{ $stage->id }}"
                                    @if(isset($step) && $step->stages->contains($stage->id)) selected @endif
                                    >{{ $stage->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary">{{__('Create')}}</button>
                </form>
            </div>
        </div>
    </div>
@endsection