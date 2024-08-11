@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h1>{{__('nomenclature')}}</h1>
                <h3>{{$nomenclature->name}}</h3>
                <a class="text-right" href="{{ route('nomenclaturs.show', $nomenclature->id) }}">{{__('Back')}}</a>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <h1>{{__('doc')}}</h1>
                <form method="POST" action="{{ route('docs.store') }}">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <input type="hidden" name="nomenclature_id" value="{{$nomenclature->id}}">
                    <div class="form-group">
                        <label for="name">{{__('Name')}}</label>
                        <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}">
                    </div>
                    <div class="form-group">
                        <label for="description">{{__('Description')}}</label>
                        <textarea class="form-control" id="description" name="description">{{ old('description') }}</textarea>
                    </div>
                    <div class="form-group">
                        <label for="file">{{__('File')}}</label>
                        <input type="file" class="form-control" id="file" name="file">
                    </div>
                    <?php $types = \App\Models\Type::orderBy('id', 'desc')->get(); ?>
                    <div class="form-group">
                        <label for="type">{{__('Type')}}</label>
                        <select class="form-control" id="type" name="type">
                            @foreach($types as $type)
                                <option value="{{$type->id}}">{{$type->name}}</option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">{{__('Create')}}</button>
                </form>
            </div>
        </div>
    </div>
@endsection