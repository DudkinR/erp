@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h1>{{__('procedures')}}</h1>
                 <form method="POST" action="{{ route('procedures.store') }}">
        @csrf

        <div class="mb-3">
            <label for="name" class="form-label">{{ __('Name') }}</label>
            <input type="text" name="name" id="name" 
                   class="form-control" value="{{ old('name') }}" required>
        </div>

        <div class="mb-3">
            <label for="description" class="form-label">{{ __('Description') }}</label>
            <textarea name="description" id="description" 
                      class="form-control" rows="4">{{ old('description') }}</textarea>
        </div>

        <button type="submit" class="btn btn-primary">{{ __('Save') }}</button>
    </form>

            </div>
        </div>
    </div>
@endsection