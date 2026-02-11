@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <h1>{{ __('Edit Word') }}</h1>

            <form method="POST" action="{{ route('words.update', $word) }}">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label for="bedword" class="form-label">{{ __('Bedword') }}</label>
                    <input type="text" name="bedword" id="bedword" class="form-control" 
                           value="{{ old('bedword', $word->bedword) }}" maxlength="128" required>
                </div>

                <div class="mb-3">
                    <label for="comment" class="form-label">{{ __('Comment') }}</label>
                    <input type="text" name="comment" id="comment" class="form-control" 
                           value="{{ old('comment', $word->comment) }}" maxlength="128">
                </div>

                <div class="mb-3">
                    <label for="type" class="form-label">{{ __('Type') }}</label>
                    <select name="type" id="type" class="form-select">
                        <option value="1" @selected(old('type', $word->type) == 1)>{{ __('Type 1') }}</option>
                        <option value="2" @selected(old('type', $word->type) == 2)>{{ __('Type 2') }}</option>
                        <option value="3" @selected(old('type', $word->type) == 3)>{{ __('Type 3') }}</option>
                    </select>
                </div>

                <button type="submit" class="btn btn-primary w-100">
                    {{ __('Update') }}
                </button>
            </form>
        </div>
    </div>
</div>
@endsection