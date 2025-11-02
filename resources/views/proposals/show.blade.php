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
            <h1>{{__('Proposal Details')}}</h1>
                <a class="btn btn-light w-100" href="{{ route('proposals.index') }}">{{__('Back')}}</a>
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
                        <a class="btn btn-light w-100" href="{{ route('proposals.consider', $proposal->id) }}">{{__('Consider Proposal')}}</a>
                    </div>
               </div>
            </div>
        </div>
   </div>
@endsection
