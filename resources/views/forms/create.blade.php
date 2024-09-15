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
                <h1>{{__('Procedure')}}</h1>
                <a href="{{ route('forms.index') }}" class="btn btn-light w-100">{{__('Back')}}</a>
                <form method="POST" action="{{ route('forms.store') }}">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <div class="form-group">
                        <label for="name">{{__('Name')}}</label>
                        <input type="text" class="form-control" id="name" name="name" required>
                    </div>
                    <div class="form-group">
                        <label for="description">{{__('Description')}}</label>
                        <textarea class="form-control" id="description" name="description" rows="10"   required></textarea>
                    </div>
                    <div class="form-group">
                        <label for="division">{{__('Division')}}</label>
                        <select id="division" class="form-control">
                            <option value="" selected>{{__('All')}}</option>
                            @foreach($divisions as $division)
                                <option value="{{ $division->id }}" style="font-weight: bold;">{{ $division->name }}</option>
                                @php $children = $division->children; $prefix=$division->name."  "; @endphp
                                @foreach($children as $child)
                                    <option value="{{ $child->id }}">{{ $prefix . $child->name }}</option>
                                    @if($child->children->count() > 0)
                                        @include('partials.division-options', ['children' => $child->children, 'prefix' => $prefix . '- '])
                                    @endif
                                @endforeach
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="status">{{__('Status')}}</label>
                        <select id="status" class="form-control" name="status">
                            <option value="0" selected
                            >{{__('Draft')}}</option>
                            <option value="1">{{__('Active')}}</option>
                            
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">{{__('Create')}}</button>
                </form>
            </div>
        </div>
    </div>
@endsection