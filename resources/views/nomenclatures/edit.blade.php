@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h1>{{__('nomenclature')}}</h1>
                <a class="text-right btn btn-primary" href="{{ route('nomenclaturs.index') }}">{{__('Back')}}</a>
            </div>
        </div>
        <div class="row">
                <form method="POST" action="{{ route('nomenclaturs.update',$nomenclature) }}">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <input type="hidden" name="_method" value="PUT">
                    <div class="form-group">
                        <label for="name">{{__('Name')}}</label>
                        <input type="text" class="form-control" id="name" name="name" value="{{ $nomenclature->name }}">
                    </div>
                    <div class="form-group">
                        <label for="article">{{__('Article')}}</label>
                        <input type="text" class="form-control" id="article" name="article" value="{{ $nomenclature->article }}">
                    </div>
                    <div class="form-group">
                        <label for="description">{{__('Description')}}</label>
                        <textarea class="form-control" id="description" name="description">{{ $nomenclature->description }}</textarea>
                    </div>
                    <div class="form-group">
                        <label for="image">{{__('Image')}}</label>
                        <input type="file" class="form-control" id="image" name="image" value="{{ $nomenclature->image }}">
                    </div>
                    <div class="form-group">
                        <label for="types">{{__('Types')}}</label>
                        <select class="form-control" id="types" name="types[]" multiple>
                            <?php $types = App\Models\Type::all(); ?>
                            @foreach($types as $type)
                                <option value="{{ $type->id }}" @if($nomenclature->types->contains($type)) selected @endif>{{ $type->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary">{{__('Create')}}</button>
                </form>
        </div>
    </div>
@endsection