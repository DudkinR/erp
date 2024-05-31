@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
            <h1>{{__('products')}}</h1>
                <a class="text-right
                " href="{{ route('products.index') }}">Back</a>
            </div>  
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="card">
               </div>
            </div>
        </div>
   </div>
@endsection