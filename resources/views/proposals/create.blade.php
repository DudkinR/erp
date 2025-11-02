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
                <form method="POST" action="{{ route('proposals.store') }}">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">

                    <div class="mb-3">
                        <label for="title" class="form-label">{{__('Title')}}</label>
                        <input type="text" class="form-control" id="title" name="title" required>
                    </div>
                    <div class="mb-3">
                        <label for="description" class="form-label">{{__('Description')}}</label>
                        <textarea class="form-control" id="description" name="description" rows="4" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="proposal" class="form-label">{{__('Proposal')}}</label>
                        <textarea class="form-control" id="proposal" name="proposal" rows="4" required></textarea>
                    </div>
     
                    <button type="submit" class="btn btn-primary w-100">{{__('Create')}}</button>
                </form>
            </div>
        </div>
    </div>
@endsection

