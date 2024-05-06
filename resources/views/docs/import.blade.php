@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h1>{{__('Doc')}}</h1>
                <form method="POST" action="{{ route('docs.importData') }}"  enctype="multipart/form-data">
                    @csrf
                    <div class="form-group row"> 
                        <div class="col-md-6">
                        <label for="folder" class="col-md-4 col-form-label text-md-right">{{ __('Folder') }}</label>
                       
                            <input id="folder" type="text" class="form-control" name="folder" value="{{ old('folder') }}" required autofocus>
                        </div>

                   
                        <div class="col-md-6">
                            <input type="submit" value="{{__('Scan')}}" class="btn btn-primary w-100">
                    </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection