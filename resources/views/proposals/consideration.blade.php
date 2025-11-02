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
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">{{ $proposal->title }}</h5>
                        <p class="card-text"><strong>{{__('Description')}}:</strong> {{ $proposal->description }}</p>
                        <p class="card-text"><strong>{{__('Proposal')}}:</strong> {{ $proposal->proposal }}</p>
                        <p class="card-text"><strong>{{__('Status')}}:</strong> {{ $proposal->status }}</p>
                        <p class="card-text"><strong>{{__('Decision')}}:</strong> {{ $proposal->decision }}</p>
                        <p class="card-text"><strong>{{__('Created At')}}:</strong> {{ $proposal->created_at }}</p>
                        <p class="card-text"><strong>{{__('Updated At')}}:</strong> {{ $proposal->updated_at }}</p>
                   </div>
               </div>
            </div>
        </div>
        <div class="row">
           @foreach($actions as $consideration)
            <div class="col-md-12 mt-3">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">{{ $consideration->title }}</h5>
                        <p class="card-text"><strong>{{__('Content')}}:</strong> {{ $consideration->content }}</p>
                        <p class="card-text"><strong>{{__('Responsible')}}:</strong> {{ $consideration->responsible }}</p>
                        <p class="card-text"><strong>{{__('Deadline')}}:</strong> {{ $consideration->deadline }}</p>
                        <p class="card-text"><small class="text-muted">{{__('Created At')}}: {{ $consideration->created_at }}</small></p>
                        <p class="card-text"><small class="text-muted">{{__('Updated At')}}: {{ $consideration->updated_at }}</small></p>
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('considerations.edit', $consideration->id) }}" class="btn btn-primary">{{__('Edit')}}</a>
                            <form action="{{ route('considerations.destroy', $consideration->id) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger">{{__('Delete')}}</button>
                            </form>
                        </div>
                   </div>
               </div>
            </div>
            @endforeach
        </div>
        <div class="row">
            <div class="col-md-12">
                <button data-toggle="modal" data-target="#addConsiderationModal" class="btn btn-primary w-100 mt-4">{{__('Add Consideration')}}</button>
            </div>
        </div>
        <div class="modal fade" id="addConsiderationModal" tabindex="-1" role="dialog" aria-labelledby="addConsiderationModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addConsiderationModalLabel">{{__('Add Consideration')}}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form method="POST" action="{{ route('considerations.store') }}">
                            @csrf
                            <input type="hidden" name="proposal_id" value="{{ $proposal->id }}">
                            <div class="mb-3">
                                <label for="title" class="form-label">{{__('Title')}}</label>
                                <input type="text" class="form-control" id="title" name="title" required>
                            </div>
                            <div class="mb-3">
                                <label for="content" class="form-label">{{__('Content')}}</label>
                                <textarea class="form-control" id="content" name="content" rows="4" required></textarea>
                            </div>
                            <div class="mb-3">
                                <label for="responsible" class="form-label">{{__('Responsible')}}</label>
                                <input type="text" class="form-control" id="responsible" name="responsible" required>
                            </div>
                            <div class="mb-3">
                                <label for="deadline" class="form-label">{{__('Deadline')}}</label>
                                <input type="date" class="form-control" id="deadline" name="deadline" required>
                            </div>
                            <button type="submit" class="btn btn-primary w-100">{{__('Add')}}</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
