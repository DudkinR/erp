@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
            <h1>{{__('procedures')}}</h1>
                  <form method="POST" action="{{ route('procedures.update', $procedure->id) }}">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label for="name" class="form-label">{{ __('Name') }}</label>
                        <input type="text" name="name" id="name" 
                            class="form-control" value="{{ old('name', $procedure->name) }}" required>
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">{{ __('Description') }}</label>
                        <textarea name="description" id="description" 
                                class="form-control" rows="4">{{ old('description', $procedure->description) }}</textarea>
                    </div>

                    <button type="submit" class="btn btn-success">{{ __('Update') }}</button>
                </form>

            </div>
        </div>
    </div>
@endsection