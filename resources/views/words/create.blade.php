@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <h1>{{ __('Create Word') }}</h1>

            <form method="POST" action="{{ route('words.store') }}">
                @csrf

                <div class="mb-3">
                    <label for="bedword" class="form-label">{{ __('Bedword') }}</label>
                    <input type="text" name="bedword" id="bedword" class="form-control" maxlength="128" required>
                </div>

                <div class="mb-3">
                    <label for="comment" class="form-label">{{ __('Comment') }}</label>
                    <input type="text" name="comment" id="comment" class="form-control" maxlength="128">
                </div>

                <div class="mb-3">
    <label for="type" class="form-label">{{ __('Type') }}</label>
    <select name="type" id="type" class="form-select">
        <option value="1" style="color:gold;">{{ __('Yellow') }}</option>
        <option value="2" style="color:red;">{{ __('Red') }}</option>
        <option value="3" style="color:blue;">{{ __('Blue') }}</option>
    </select>
</div>


                <button type="submit" class="btn btn-primary w-100">
                    {{ __('Save') }}
                </button>
            </form>
        </div>
    </div>
</div>
@endsection