@extends('layouts.app')
@section('content')
    <div class="container">
        @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif
        <div class="row">
            <div class="col-md-12">
                <h1>{{__('_______')}}</h1>
                <form method="POST" action="{{ route('_______.importData') }}"  enctype="multipart/form-data">
                @include('layouts.import_block')
                </form>
            </div>
        </div>
    </div>
@endsection