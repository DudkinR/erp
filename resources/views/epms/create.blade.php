@extends('layouts.app')
@section('content')
    <div class="container">
               @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        @if(session('success'))
        <div class="alert alert-success">{{ __(session('success')) }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ __(session('error')) }}</div>
    @endif
        <div class="row">
            <div class="col-md-12">
                <h1>{{__('EPM')}}</h1>
                <form method="POST" action="{{ route('epm.store') }}">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <div class="form-group">
                        <label for="name">{{__('Name')}}</label>
                        <textarea class="form-control" id="name" name="name" placeholder="{{__('Enter name')}}"></textarea>
                    </div>
                    <div class="form-group">
                        <label for="description">{{__('Description')}}</label>
                        <textarea class="form-control" id="description" name="description" placeholder="{{__('Enter description')}}"></textarea>
                    </div>
                    <div class="form-group">
                        <label for="division">{{__('Division')}}</label>
                        @php $divisions = \App\Models\Division::all(); @endphp
                        <select class="form-control" id="division" name="division">
                            <option value="">{{__('Select Division')}}</option>
                            @foreach($divisions as $division)
                                <option value="{{ $division->id }}">{{ $division->name }}</option>
                            @endforeach
                        </select>

                        
                    </div>
                    @php $wanoareas = \App\Models\WANOAREA::all(); @endphp
                    <div class="form-group">
                        <label for="wanoarea">{{__('Wanoarea')}}</label>
                        <select class="form-control" id="wanoarea" name="wanoarea">
                            <option value="">{{__('Select Wanoarea')}}</option>
                            @foreach($wanoareas as $wanoarea)
                                <option value="{{ $wanoarea->id }}">{{ $wanoarea->name }}</option>
                            @endforeach
                        </select>
                    </div>

                        
                        
                    <button type="submit" class="btn btn-primary w-100">{{__('Create')}}</button>
                </form>
            </div>
        </div>
    </div>
@endsection