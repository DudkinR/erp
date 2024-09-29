@extends('layouts.app')
@section('content')
    <div class="container">        
        <div class="row">
            <div class="col-md-12">
                <input type="text" id="search" class="form-control" placeholder="{{__('Search')}}">
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
            <h1>{{__('Types')}}</h1>
            @if(Auth::user()->hasRole('quality-engineer','admin'))
           
                <a class="text-right" href="{{ route('types.create') }}">{{__('Create')}}</a>
            @endif </div>
        </div>  
        <div class="container" id = "types">
        @foreach ($types as $type)
            <div class="row" style="background-color: {{ $type->color }}">
                <div class="col-md-3">
                    <h2>{{ $type->name }}</h2>
                </div>
                <div class="col-md-4">
                    <p>{{ $type->description }}</p>
                                  </div>
                <div class="col-md-1">
                    <p>{{ $type->slug }}</p>
                </div>
                <div class="col-md-2">
                    @if(is_file (public_path('storage/types/'.$type->icon)))    
                        <img src="{{ asset('storage/types/'.$type->icon) }}" alt="{{ $type->name }}" style="width: 100px; height: 100px;">
                    @else
                        img
                    @endif
                </div>
                <div class="col-md-2"> 
                   <a class = "btn btn-success w-100" href="{{ route('types.show', $type->id) }}">{{__('Show')}}</a>  
                   @if(Auth::user()->hasRole('quality-engineer','admin'))
               
                    <a class="btn btn-warning w-100" href="{{ route('types.edit', $type->id) }}">{{__('Edit')}}</a>
                   
                    <form method="POST" action="{{ route('types.destroy', $type->id) }}">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <input type="hidden" name="_method" value="DELETE">
                        <button type="submit" class="btn btn-danger w-100" >{{__('Delete')}}</button>
                    </form>
                    <a class="btn btn-info w-100" href="{{ route('types.create', ['parent_id' => $type->id]) }}">{{__('Create')}}</a>
                 
        @endif
                </div>
            </div>
        @endforeach  
        </div>
    </div>
    <script>
        const types = @json($types);
        var vtypes = types; 
        $(document).ready(function(){
            $("#search").on("keyup", function() {
                var value = $(this).val().toLowerCase();
                // search where name description or slug contains the value
                vtypes = 
                    types.filter(type => 
                        type.name.toLowerCase().includes(value) || 
                        type.description.toLowerCase().includes(value) || 
                        type.slug.toLowerCase().includes(value)
                    );
                    
                show_types();
            });
        });
        function show_types(){
            var html = '';
            const container = document.getElementById('types');
            container.innerHTML = '';
            vtypes.forEach(type => {
                html += '<div class="row" style="background-color: '+type.color+'">';
                html += '<div class="col-md-3">';
                html += '<h2>'+type.name+'</h2>';
                html += '</div>';
                html += '<div class="col-md-4">';
                html += '<p>'+type.description+'</p>';
                html += '</div>';
                html += '<div class="col-md-1">';
                html += '<p>'+type.slug+'</p>';
                html += '</div>';
                html += '<div class="col-md-2">';
                html += '<img src="/storage/types/'+type.icon+'" alt="'+type.name+'" style="width: 100px; height: 100px;">';
                html += '</div>';
                html += '<div class="col-md-2">';
                html += '<a class = "btn btn-success w-100" href="/types/'+type.id+'">Show</a>';
                html += '<a class="btn btn-warning w-100" href="/types/'+type.id+'/edit">Edit</a>';
                html += '<form method="POST" action="/types/'+type.id+'">';
                html += '<input type="hidden" name="_token" value="{{ csrf_token() }}">';
                html += '<input type="hidden" name="_method" value="DELETE">';
                html += '<button type="submit" class="btn btn-danger w-100" >Delete</button>';
                html += '</form>';
                html += '<a class="btn btn-info w-100" href="/types/create?parent_id='+type.id+'">Create</a>';
                html += '</div>';
                html += '</div>';
            });
            
            container.innerHTML = html;
        }

            
    </script>
@endsection