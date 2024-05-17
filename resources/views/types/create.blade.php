@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h1>{{__('Type create')}}</h1>
                <a class="text-right" href="{{ route('types.index') }}">{{__('Back')}}</a>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <form method="POST" action="{{ route('types.store') }}" enctype="multipart/form-data">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <div class="form-group">
                        <label for="name" >{{__('Name')}}</label>
                        
                            <input id="name" type="text" class="form-control" name="name" value="{{ old('name') }}"  autofocus>
                      
                    </div>
                    <div class="form-group">
                        <label for="description" >{{__('Description')}}</label>
                        
                            <textarea id="description" rows=7 class="form-control" name="description"  autofocus>{{ old('description') }}</textarea>
                                
                    </div>
                    <div class="form-group">
                        <label for="icon" >{{__('Icon')}}</label>
                        
                            <input id="icon" type="file" class="form-control" name="icon" value="{{ old('icon') }}"  autofocus>
                      
                    </div>
                    <div class="form-group">
                        <label for="color" >{{__('Color')}}</label>
                        
                            <input id="color" type="color" class="form-control" name="color" value="#FFFFFF"  autofocus>
                      
                    </div>
                    <div class="form-group">
                        <label for="slug" >{{__('Slug')}}</label>
                        
                            <input id="slug" type="text" class="form-control" name="slug" value="{{ old('slug') }}"  autofocus>
                        
                    </div>
                    <div class="form-group">
                        <label for="parent_id" >{{__('Parent')}}</label>
                        
                            <select class="form-control" id="parent_id" name="parent_id">
                                <option value="0">{{__('First parent')}}</option>
                                <?php $types = \App\Models\Type::all(); ?>
                                @foreach($types as $type)
                                    <option value="{{ $type->id }}">{{ $type->name }}</option>
                                @endforeach
                            </select>
                            <a href="{{ route('types.create') }}">{{__('Create new parent')}}</a>
                       
                    </div>
                    <button type="submit" class="btn btn-primary">{{__('Create')}}</button>
                </form>
            </div>
        </div>
    </div>
@endsection