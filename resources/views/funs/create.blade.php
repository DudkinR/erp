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
                <h1>{{__('New Function')}}</h1>
                <form method="POST" action="{{ route('funs.store') }}">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    @if($gl)
                        <input type="hidden" name="gl" value="{{ $gl }}">
                    @endif
                    <div class="container border bg-secondary">
                        <div class="form-group">
                            <label for="name"> {{__('Function Name')}}
                                </label>
                            <input type="text" class="form-control" id="name" name="name">
                        </div>
                        <div class="form-group">
                            <label for="description">{{__('Description')}}</label>
                            <textarea class="form-control" id="description" name="description"></textarea>
                        </div>
                    </div>
                    <div class="form-group">
                        @php 
                            $fun_all = \App\Models\Fun::orderBy('id', 'desc')->get();
                        @endphp
                        <label for="exist">{{__('Exists Function')}}</label>
                        <select class="form-control" id="exist" name="exist">
                            <option value="0">{{__('None')}}</option>
                            @foreach($fun_all as $fun)
                                <option value="{{ $fun->id }}">{{ $fun->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="objectives">{{__('Objectives')}}</label>
                        @php 
                            $objective_id = Request::get('objective_id');
                        @endphp
                        <select class="form-control" id="objectives" name="objectives[]" multiple size = 5>
                            @foreach($objs as $objective)
                                <option value="{{ $objective->id }}"
                                @if($objective_id == $objective->id)
                                    selected
                                @endif
                                    >{{ $objective->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="positions">{{__('Positions')}}</label>
                        <select class="form-control" id="positions" name="positions[]" multiple size = 5>
                            @foreach($positions as $position)
                                <option value="{{ $position->id }}">{{ $position->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary">{{__('Create')}}</button>
                </form>
            </div>
        </div>
    </div>

@endsection
