@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h1>{{__('Buildings')}}</h1>
                <form method="POST" action="{{ route('buildings.importData') }}"  enctype="multipart/form-data">
                    @csrf
                    
                @include('layouts.import_block')
                </form>
            </div>
        </div>
    </div>
@endsection