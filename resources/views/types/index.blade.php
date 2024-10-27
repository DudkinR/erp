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
           
                <a class="btn btn-success w-100" href="{{ route('types.create') }}">{{__('Create')}}</a>
            @endif </div>
        </div>  
        <div class="container" id = "types">
    
 
        </div>
    </div>
    <script>
        const types = @json($types);
        var vtypes = types; 
        $(document).ready(function(){
    $("#search").on("keyup", function() {
        var value = $(this).val().toLowerCase();
        // search where name, description or slug contains the value
        vtypes = types.filter(type => 
            (type.name && type.name.toLowerCase().includes(value)) || 
            (type.description && type.description.toLowerCase().includes(value)) || 
            (type.slug && type.slug.toLowerCase().includes(value))
        );
        
        show_types();
    });
});
function show_types() {
    var html = '';
    const container = document.getElementById('types');
    container.innerHTML = '';
    vtypes.forEach(type => {
        html += show_row(type);
    });
    container.innerHTML = html;
}
function show_children(parent_id){
    var children = types.filter(type => type.parent_id == parent_id);
    var html = '';
    const container = document.getElementById('types');
    container.innerHTML = '';
    children.forEach(type => {
        html += show_row(type);
    });
    container.innerHTML = html;
}
function show_row(type)  {
    var html = '';
    html += '<div class="row align-items-center" style="background-color: ' + type.color + '; margin-bottom: 10px; padding: 10px;">';
    html += '<div class="col-md-3">';
    html += '<h6 >' + type.name + '</h6>';
    //button for showing children
    html += '<button class="btn btn-primary" onclick="show_children(' + type.id + ')"> {{__('Show Children')}}</button>';
    // button  children parent_id
    html += '<button class="btn btn-primary" onclick="show_children(' + type.parent_id + ')"> {{__('Show all')}}</button>';
    html += '</div>';
    html += '<div class="col-md-4">';
    html += '<p style="margin: 0;">' + type.description + '</p>';
    html += '</div>';
    html += '<div class="col-md-1">';
    html += '<p style="margin: 0;">' + type.slug + '</p>';
    html += '</div>';
    html += '<div class="col-md-2">';
    if (type.icon) { // Перевірка наявності іконки
        html += '<img src="/storage/types/' + type.icon + '" alt="' + type.name + '" style="width: 100px; height: 100px;">';
    } else {
        html += '<div style="width: 100px; height: 100px; background-color: #eaeaea; display: flex; align-items: center; justify-content: center;">';
        html += '<span>No Image</span>';
        html += '</div>';
    }
    html += '</div>';
    @if(Auth::user()->hasRole('quality-engineer','admin'))
    html += '<div class="col-md-2 d-flex flex-column">';
    html += '<a class="btn btn-success w-100" href="/types/' + type.id + '">{{__('View')}}</a>';
    html += '<a class="btn btn-warning w-100" href="/types/' + type.id + '/edit"> {{__('Edit')}}</a>';
    html += '<form method="POST" action="/types/' + type.id + '" style="margin: 0;">';
    html += '<input type="hidden" name="_token" value="{{ csrf_token() }}">';
    html += '<input type="hidden" name="_method" value="DELETE">';
    html += '<button type="submit" class="btn btn-danger w-100"> {{__('Delete')}}</button>';
    html += '</ form>';
    html += '<a class="btn btn-info  w-100" href="/types/create?parent_id=' + type.id + '"> {{__('Create Subtype')}}</a>';
    html += '</div>';
    @endif
    html += '</div>';
    return html;
}

show_types();            
    </script>
@endsection