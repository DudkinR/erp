@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h1>{{__('Load Personal')}}</h1>
                <form method="POST" action="{{ route('personal.importData') }}"  enctype="multipart/form-data">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <div class="form-group">
                        <label for="file">{{__('File csv')}}</label>
                        <input type="file" class="form-control" id="file" name="file">
                    </div>
                    <button type="submit" class="btn btn-primary">
                        {{__('Load')}}
                    </button>
                </form>
            </div>
        </div>
    </div>
@endsection