@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h1>{{__('Dimensions')}}</h1>
                <form method="POST" action="{{ route('dimensions.importData') }}"  enctype="multipart/form-data">
                @include('layouts.import_block')
                </form>
            </div>
        </div>
    </div>
@endsection