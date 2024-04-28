@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
            <h1>{{__('Dimension edit')}}</h1>
                <form method="POST" action="{{ route('dimensions.update',$dimension) }}">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <input type="hidden" name="_method" value="PUT">
                    <div class="form-group row">
                        <label for="name" class="col-md-4 col-form-label text-md-right">{{__('Name')}}</label>
                        <div class="col-md-6">
                            <input id="name" type="text" class="form-control" name="name" value="{{ $dimension->name }}" required autofocus>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="description" class="col-md-4 col-form-label text-md-right">{{__('Description')}}</label>
                        <div class="col-md-6">
                            <input id="description" type="text" class="form-control" name="description" value="{{ $dimension->description }}" required autofocus>
                        </div>
                    </div>
                    <?php $controls = \App\Control::all(); ?>
                    <div class="form-group row">
                        <label for="control_id" class="col-md-4 col-form-label text-md-right">{{__('Control')}}</label>
                        <div class="col-md-6">
                            <select id="control_id" class="form-control" name="control_id[]" multiple>
                                <option value="">{{__('Select')}}</option>  
                                @foreach($controls as $control)
                                    <option value="{{ $control->id }}"
                                    @if($dimension->controls->contains($control)) selected @endif                                           
                                        >{{ $control->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                   
                    <button type="submit" class="btn btn-primary">{{__('Create')}}</button>
                </form>
            </div>
        </div>
    </div>
@endsection