@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h1>{{__('EPMdata')}}</h1>
                <form method="POST" action="{{ route('epmdata.store') }}">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <input type="hidden" name="epm_id" value="{{ request('epm_id') }}">
                    <div class="form-group">
                        <label for="date">{{__('Date')}}</label>
                        <input type="date" class="form-control" id="date" name="date" value="{{ old('date') }}">
                    </div>
                    <div class="form-group">
                        <label for="data">{{__('Data')}}</label>
                        <input type="data" class="form-control" id="data" name="data" value="{{ old('data') }}">
                    </div>
                    <button type="submit" class="btn btn-primary w-100">{{__('Create')}}</button>
                </form>
            </div>
        </div>
    </div>
@endsection