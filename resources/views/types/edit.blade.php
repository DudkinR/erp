@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
            <h1>{{__('Types')}}</h1>
                <a class="text-right" href="{{ route('types.create') }}">{{__('Create')}}</a>
                <a class="text-right" href="{{ route('types.index') }}">{{__('Back')}}</a>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <form method="POST" action="{{ route('types.update',$type) }}">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <input type="hidden" name="_method" value="PUT">
                    <div class="form-group ">
                        <label for="name" >{{__('Name')}}</label>
                        
                            <input id="name" type="text" class="form-control" name="name" value= "{{ $type->name }}"  autofocus>
                        
                    </div>
                    <div class="form-group ">
                        <label for="description" >{{__('Description')}}</label>
                       
                            <textarea id="description" rows=7 class="form-control" name="description"  autofocus>{{ $type->description }}</textarea>
                            
                    </div>
                    <div class="form-group ">
                        <label for="icon" >{{__('Icon')}}</label>
                        
                            <input id="icon" type="file" class="form-control" name="icon" value= "{{ $type->icon }}"  autofocus>
                        
                    </div>
                    <div class="form-group ">
                        <label for="color" >{{__('Color')}}</label>
                      
                            <input id="color" type="color" class="form-control" name="color" value= "{{ $type->color }}"  autofocus>
                        
                    </div>
                    <div class="form-group ">
                        <label for="slug" >{{__('Slug')}}</label>
                        <input id="slug" type="text" class="form-control" name="slug" value= "{{ $type->slug }}"  autofocus>
                        
                    </div>
                    <div class="form-group ">
                        <label for="parent_id" >{{__('Parent')}}</label>
                        
                            <select class="form-control" id="parent_id" name="parent_id">
                                <option value="0">{{__('First parent')}}</option>
                                <?php $types = \App\Models\Type::all(); ?>
                                @foreach($types as $type)
                                    <option value="{{ $type->id }}"
                                        @if($type->id == $type->parent_id)
                                            selected
                                        @endif
                                    >{{ $type->name }}</option>
                                @endforeach
                            </select>
                            <a href="{{ route('types.create') }}">{{__('Create new parent')}}</a>
                       
                    </div>
                    <button type="submit" class="btn btn-primary">{{__('Update')}}</button>
                </form>
            </div>
        </div>
    </div>
    
@endsection