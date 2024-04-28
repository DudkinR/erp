@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h1>{{__('Type create')}}</h1>
                <form method="POST" action="{{ route('types.store') }}" enctype="multipart/form-data">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <div class="form-group row">
                        <label for="name" class="col-md-4 col-form-label text-md-right">{{__('Name')}}</label>
                        <div class="col-md-6">
                            <input id="name" type="text" class="form-control" name="name" value="{{ old('name') }}"  autofocus>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="description" class="col-md-4 col-form-label text-md-right">{{__('Description')}}</label>
                        <div class="col-md-6">
                            <textarea id="description" rows=7 class="form-control" name="description"  autofocus>{{ old('description') }}</textarea>
                                </div>
                    </div>
                    <div class="form-group row">
                        <label for="icon" class="col-md-4 col-form-label text-md-right">{{__('Icon')}}</label>
                        <div class="col-md-6">
                            <input id="icon" type="file" class="form-control" name="icon" value="{{ old('icon') }}"  autofocus>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="color" class="col-md-4 col-form-label text-md-right">{{__('Color')}}</label>
                        <div class="col-md-6">
                            <input id="color" type="color" class="form-control" name="color" value="#FFFFFF"  autofocus>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="slug" class="col-md-4 col-form-label text-md-right">{{__('Slug')}}</label>
                        <div class="col-md-6">
                            <input id="slug" type="text" class="form-control" name="slug" value="{{ old('slug') }}"  autofocus>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="parent_id" class="col-md-4 col-form-label text-md-right">{{__('Parent')}}</label>
                        <div class="col-md-6">
                            <select class="form-control" id="parent_id" name="parent_id">
                                <option value="0">{{__('First parent')}}</option>
                                <?php $types = \App\Models\Type::all(); ?>
                                @foreach($types as $type)
                                    <option value="{{ $type->id }}">{{ $type->name }}</option>
                                @endforeach
                            </select>
                            <a href="{{ route('types.create') }}">{{__('Create new parent')}}</a>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary">{{__('Create')}}</button>
                </form>
            </div>
        </div>
    </div>
@endsection