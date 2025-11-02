@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <a href="{{ route('proposals.show', $consideration->proposal_id) }}" class="btn btn-secondary mb-3">{{__('Back to Proposal')}}</a>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
            
                <h1>{{__('Edit Consideration')}}</h1>
                <form method="POST" action="{{ route('considerations.update', $consideration->id) }}">
                    @method('PUT')
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <input type="hidden" name="action_id" value="{{ $consideration->id }}">
                    <div class="mb-3">
                        <label for="content" class="form-label">{{__('Content')}}</label>
                        <textarea class="form-control" id="content" name="content" rows="4" required>{{ old('content', $consideration->title) }}</textarea>
                    </div>
                    <div class="mb-3">
                        <label for="responsible" class="form-label">{{__('Responsible')}}</label>
                        <input type="text" class="form-control" id="responsible" name="responsible"  value="{{ old('responsible', $consideration->responsible) }}" required>
                    </div>
                    <div class="mb-3">
                        <label for="deadline" class="form-label">{{__('Deadline')}}</label>
                        Call to a member function format() on string
                        @php 
                            // Ensure $consideration->deadline is a Carbon instance
                            use Carbon\Carbon;
                            if (!($consideration->deadline instanceof Carbon)) {
                                $consideration->deadline = Carbon::parse($consideration->deadline);
                            }
                        @endphp
                        <input type="date" class="form-control" id="deadline" name="deadline"  value="{{ old('deadline', $consideration->deadline->format('Y-m-d')) }}" required>
                    </div>
                    <div class="mb-3">
                        <label for="status" class="form-label">{{__('Status')}}</label>
                        <select class="form-control" id="status" name="status" required>
                            <option value="в процесі" {{ old('status', $consideration->status) == 'в процесі' ? 'selected' : '' }}>{{__('In Progress')}}</option>
                            <option value="виконано" {{ old('status', $consideration->status) == 'виконано' ? 'selected' : '' }}>{{__('Completed')}}</option>
                            <option value="не виконано" {{ old('status', $consideration->status) == 'не виконано' ? 'selected' : '' }}>{{__('Not Completed')}}</option> 
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="result_description" class="form-label">{{__('Result Description')}}</label>
                        <textarea class="form-control" id="result_description" name="result_description" rows="4" required>{{ old('result_description', $consideration->result_description) }}</textarea>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">{{__('Update')}}</button>
                </form>
            </div>
        </div>
        <div class="row">
            @foreach($efficiency_criteria as $criterion)
            <div class="col-md-4">
                <div class="card mb-4">
                    <div class="card-body">
                        <h5 class="card-title">{{ $criterion->name }}</h5>
                        <p class="card-text"><strong>{{__('Weight')}}:</strong> {{ $criterion->weight }}</p>
                        <p class="card-text"><strong>{{__('Unit')}}:</strong> {{ $criterion->unit }}</p>
                        <a href="{{ route('efficiency_criteria.edit', $criterion->id) }}" class="btn btn-warning btn-sm">{{__('Edit')}}</a>
                        <form method="POST" action="{{ route('efficiency_criteria.destroy', $criterion->id) }}" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm">{{__('Delete')}}</button>
                        </form>

                    </div>
                </div>
            </div>
            @endforeach

            <div class="col-md-12">
                <button type="button" class="btn btn-success w-100" data-bs-toggle="modal" data-bs-target="#addCriterionModal">
                    {{__('Add Efficiency Criterion')}}
                </button>
            </div>

            <div class="modal fade" id="addCriterionModal" tabindex="-1" aria-labelledby="addCriterionModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="addCriterionModalLabel">{{__('Add Efficiency Criterion')}}</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form method="POST" action="{{ route('efficiency_criteria.store') }}">
                                @csrf
                                <input type="hidden" name="action_id" value="{{ $consideration->id }}">
                                <input type="hidden" name="proposal_id" value="{{ $consideration->proposal_id }}">
                                <div class="mb-3">
                                    <label for="name" class="form-label">{{__('Name')}}</label>
                                    <input type="text" class="form-control" id="name" name="name" required>
                                </div>
                                <div class="mb-3">
                                    <label for="weight" class="form-label">{{__('Weight')}}</label>
                                    <input type="number" step="0.01" class="form-control" id="weight" name="weight" required>
                                </div>
                                <div class="mb-3">
                                    <label for="unit" class="form-label">{{__('Unit')}}</label>
                                    <input type="text" class="form-control" id="unit" name="unit" required>
                                </div>
                                <button type="submit" class="btn btn-primary w-100">{{__('Add')}}</button>
                            </form>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

