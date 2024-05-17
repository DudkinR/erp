@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
            <h1>{{__('Criteria')}}</h1>
                <form method="POST" action="{{ route('criteria.update',$criteria) }}">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <input type="hidden" name="_method" value="PUT">
                    <div class="form-group">
                        <label for="title">{{__('Name')}}</label>
                        <input type="text" class="form-control" id="name" name="name" value="{{ $criteria->name }}">
                    </div>
                    <div class="form-group">
                        <label for="description">{{__('Description')}}</label>
                        <textarea class="form-control" id="description" name="description" rows="10" >{!! $criteria->description !!} </textarea>
                    </div>
                    <div class="form-group">
                        <label for="weight">{{__('Weight')}}</label>
                        <input type="number" class="form-control" id="weight" name="weight" value="{{ $criteria->weight }}">
                    </div>

                   
                    <button type="submit" class="btn btn-primary">{{__('Update')}}</button>
                </form>
            </div>
        </div>
    </div>
@endsection