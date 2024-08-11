@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h1>{{__('Dimension new')}}</h1>
                <form method="POST" action="{{ route('dimensions.store') }}">
                    <div class="form-group row">
                        <label for="name" class="col-md-4 col-form-label text-md-right">{{__('Name')}}</label>
                        <div class="col-md-6">
                            <input id="name" type="text" class="form-control" name="name" value="{{ old('name') }}"  autofocus>
                        </div>
                    </div>
                    <div class="form-group
                    row">
                        <label for="description" class="col-md-4 col-form-label text-md-right">{{__('Description')}}</label>
                        <div class="col-md-6">
                            <textarea id="description" rows=7 class="form-control" name="description"  autofocus>{{ old('description') }}</textarea>         
                        </div>
                    </div>
                    <?php $controls = \App\Models\Control::orderBy('id', 'desc')->get(); ?>
                    <div class="form-group row">
                        <label for="control_id" class="col-md-4 col-form-label text-md-right">{{__('Control')}}</label>
                        <div class="col-md-6">
                            <select id="control_id" class="form-control" name="control_id[]" multiple>
                                <option value="">{{__('Select')}}</option>  
                                @foreach($controls as $control)
                                    <option value="{{ $control->id }}">{{ $control->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <div class="form-group row">
                        <label for="abv" class="col-md-4 col-form-label text-md-right">{{__('ABV')}}</label>
                        <div class="col-md-6">
                            <input id="abv" type="text" class="form-control" name="abv" value="{{ old('abv') }}"  autofocus>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="kod" class="col-md-4 col-form-label text-md-right">{{__('Kod')}}</label>
                        <div class="col-md-6">
                            <input id="kod" type="text" class="form-control" name="kod" value="{{ old('kod') }}"  autofocus>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="formula" class="col-md-4 col-form-label text-md-right">{{__('Formula')}}</label>
                        <div class="col-md-6">
                            <input id="formula" type="text" class="form-control" name="formula" value="{{ old('formula') }}"  autofocus>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="unit" class="col-md-4 col-form-label text-md-right">{{__('Unit')}}</label>
                        <div class="col-md-6">
                            <input id="unit" type="text" class="form-control" name="unit" value="{{ old('unit') }}"  autofocus>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="type" class="col-md-4 col-form-label text-md-right">{{__('Type')}}</label>
                        <div class="col-md-6">
                            <input id="type" type="text" class="form-control" name="type" value="{{ old('type') }}"  autofocus>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="value" class="col-md-4 col-form-label text-md-right">{{__('Value')}}</label>
                        <div class="col-md-6">
                            <input id="value" type="number" class="form-control" name="value" value="{{ old('value') }}"  autofocus>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="min_value" class="col-md-4 col-form-label text-md-right">{{__('Min value')}}</label>
                        <div class="col-md-6">
                            <input id="min_value" type="number" class="form-control" name="min_value" value="{{ old('min_value') }}"  autofocus>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="max_value" class="col-md-4 col-form-label text-md-right">{{__('Max value')}}</label>
                        <div class="col-md-6">
                            <input id="max_value" type="number" class="form-control" name="max_value" value="{{ old('max_value') }}"  autofocus>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="step" class="col-md-4 col-form-label text-md-right">{{__('Step')}}</label>
                        <div class="col-md-6">
                            <input id="step" type="number" class="form-control" name="step" value="{{ old('step') }}"  autofocus>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="default_value" class="col-md-4 col-form-label text-md-right">{{__('Default value')}}</label>
                        <div class="col-md-6">
                            <input id="default_value" type="number" class="form-control" name="default_value" value="{{ old('default_value') }}"  autofocus>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="default_min_value" class="col-md-4 col-form-label text-md-right">{{__('Default min value')}}</label>
                        <div class="col-md-6">
                            <input id="default_min_value" type="number" class="form-control" name="default_min_value" value="{{ old('default_min_value') }}"  autofocus>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="default_max_value" class="col-md-4 col-form-label text-md-right">{{__('Default max value')}}</label>
                        <div class="col-md-6">
                            <input id="default_max_value" type="number" class="form-control" name="default_max_value" value="{{ old('default_max_value') }}"  autofocus>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="default_step" class="col-md-4 col-form-label text-md-right">{{__('Default step')}}</label>
                        <div class="col-md-6">
                            <input id="default_step" type="number" class="form-control" name="default_step" value="{{ old('default_step') }}"  autofocus>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="default_type" class="col-md-4 col-form-label text-md-right">{{__('Default type')}}</label>
                        <div class="col-md-6">
                            <input id="default_type" type="text" class="form-control" name="default_type" value="{{ old('default_type') }}"  autofocus>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="default_unit" class="col-md-4 col-form-label text-md-right">{{__('Default unit')}}</label>
                        <div class="col-md-6">
                            <input id="default_unit" type="text" class="form-control" name="default_unit" value="{{ old('default_unit') }}"  autofocus>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary">{{__('Create')}}</button>
                </form>
            </div>
        </div>
    </div>
@endsection