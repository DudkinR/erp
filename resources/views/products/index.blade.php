@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
            <h1>{{__('products')}}</h1>
                <a class="text-right" href="{{ route('products.create') }}">{{__('New')}}</a>
            </div>
        </div>    
    </div>
@endsection