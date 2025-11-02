@extends('layouts.app')
@section('content')
    <div class="container">
     <div class="row">
            <div class="col-md-12">
                <a href="{{ route('proposals.index') }}" class="btn btn-secondary mb-3">{{__('Back to Proposal')}}</a>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <h1>{{__('Create Proposal')}}</h1>
                <form method="POST" action="{{ route('proposals.update', $proposal->id) }}">
                    @method('PUT')
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">

                    <div class="mb-3">
                        <label for="title" class="form-label">{{__('Title')}}</label>
                        <input type="text" class="form-control" id="title" name="title"  value="{{ old('title', $proposal->title) }}" required>
                    </div>
                    <div class="mb-3">
                        <label for="description" class="form-label">{{__('Description')}}</label>
                        <textarea class="form-control" id="description" name="description" rows="4" required>{{ old('description', $proposal->description) }}</textarea>
                    </div>
                    <div class="mb-3">
                        <label for="proposal" class="form-label">{{__('Proposal')}}</label>
                        <textarea class="form-control" id="proposal" name="proposal" rows="4" required>{{ old('proposal', $proposal->proposal) }}</textarea>
                    </div>
                    <div class="mb-3">
                        <label for="status" class="form-label">{{__('Status')}}</label>
                        <select class="form-select" id="status" name="status" required>
                            <option value="на розгляді" {{ old('status', $proposal->status) == 'на розгляді' ? 'selected' : '' }}>{{__('На розгляді')}}</option>
                            <option value="схвалено" {{ old('status', $proposal->status) == 'схвалено' ? 'selected' : '' }}>{{__('Схвалено')}}</option>
                            <option value="відхилено" {{ old('status', $proposal->status) == 'відхилено' ? 'selected' : '' }}>{{__('Відхилено')}}</option>
                            <option value="у доопрацюванні" {{ old('status', $proposal->status) == 'у доопрацюванні' ? 'selected' : '' }}>{{__('У доопрацюванні')}}</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="decision" class="form-label">{{__('Decision')}}</label>
                        <textarea class="form-control" id="decision" name="decision" rows="4" required>{{ old('decision', $proposal->decision) }}</textarea>
                    </div>

                    <button type="submit" class="btn btn-primary w-100">{{__('Update')}}</button>
                </form>
            </div>
        </div>
    </div>
@endsection

