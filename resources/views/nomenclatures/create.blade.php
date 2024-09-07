@extends('layouts.app')
@section('content')
    <div class="container">
        @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif
        <div class="row">
            <div class="col-md-12">
                <h1>{{__('nomenclatures')}}</h1>
                <a class="text-right btn btn-primary" href="{{ route('nomenclaturs.index') }}">{{__('Back')}}</a>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <h1>{{__('nomenclature')}}</h1>
                <form method="POST" action="{{ route('nomenclaturs.store') }}" enctype="multipart/form-data">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <div class="form-group">
                        <label for="name">{{__('Name')}}</label>
                        <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}">
                    </div>
                    <div class="form-group">
                        <label for="article">{{__('Article')}}</label>
                        <input type="text" class="form-control" id="article" name="article" value="{{ old('article') }}">
                    </div>
                    <div class="form-group">
                        <label for="description">{{__('Description')}}</label>
                        <textarea class="form-control" id="description" name="description">{{ old('description') }}</textarea>  
                    </div>
                    <div class="form-group">
                        <label for="image">{{__('Image')}}</label>
                        <input type="file" class="form-control" id="image" name="image" value="{{ old('image') }}">
                    </div>
                    <div class="form-group">
                        <label for="types">{{__('Types')}}</label>
                        <select class="form-control" id="types" name="types[]" multiple>
                            <?php $types = App\Models\Type::orderBy('id', 'desc')->get(); ?>
                            @foreach($types as $type)
                                <option value="{{ $type->id }}">{{ $type->name }}</option>
                            @endforeach
                        </select>
                    </div>
                        <button type="submit" class="btn btn-primary w-100">{{__('Create')}}</button>
                </form>
            </div>
        </div>
    </div>
@endsection