@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h1>{{__('Image')}}</h1>
            </div>
        </div>
        @if($nomenclature->image)
        <div class="row">
            <div class="col-md-12">
                <img src="{{asset('storage/nomenclature/'.$nomenclature->image)}}" alt="image" class="img-thumbnail">
            </div>
        </div>
        @endif
        <div class="row">
            <div class="col-md-12">
                <form method="POST" action="{{ route('nomenclatures.img.store', $nomenclature->id) }}" enctype="multipart/form-data">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <input type="hidden" name="nomenclature_id" value="{{$nomenclature->id}}">
                    
                    <div class="form-group">
                        <label for="image">{{__('Image')}}</label>
                        <input type="file" class="form-control" id="image" name="image">
                    </div>
                    <button type="submit" class="btn btn-primary w-100">{{__('Create')}}</button>
                </form>
            </div>
        </div>
    </div>
@endsection
