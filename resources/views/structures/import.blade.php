@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h1>{{__('Load Goal')}}</h1>
                <form method="POST" action="{{ route('structure.importData') }}"  enctype="multipart/form-data">
                   <!-- resources\views\layouts\import_block.blade.php -->
                    @include('layouts.import_block')
                </form>
            </div>
        </div>
    </div>

@endsection