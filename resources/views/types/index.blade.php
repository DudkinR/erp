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
            @if(Auth::user()->hasRole('admin'))
                <a class="btn btn-success w-100" href="{{ route('types.create') }}">{{__('Create')}}</a>
            @endif
        </div>
    </div>  
    <div class="container" id="types"></div>
    <div class="container mt-4" id="orphans"></div>
</div>

<script>
    const types = @json($types);
    var vtypes = types; 

    $(document).ready(function(){
        $("#search").on("keyup", function() {
            var value = $(this).val().toLowerCase();
            vtypes = types.filter(type => 
                (type.name && type.name.toLowerCase().includes(value)) || 
                (type.description && type.description.toLowerCase().includes(value)) || 
                (type.slug && type.slug.toLowerCase().includes(value))
            );
            show_types();
        });
        show_types();
    });

    function show_types() {
        const container = document.getElementById('types');
        container.innerHTML = '';
        
        // знайти всі root (parent_id == 0)
        const roots = vtypes.filter(t => t.parent_id == 0);
        roots.forEach(root => {
            container.appendChild(build_tree(root, 0));
        });

        // знайти "сиріт" (які не мають валідного parent у vtypes)
        const orphanContainer = document.getElementById('orphans');
        orphanContainer.innerHTML = '<h3>{{__("Orphans")}}</h3>';
        const orphanTypes = vtypes.filter(t => t.parent_id != 0 && !vtypes.find(p => p.id == t.parent_id));
        orphanTypes.forEach(orphan => {
            orphanContainer.appendChild(show_row(orphan, 0));
        });
    }

    function build_tree(node, level) {
    let wrapper = document.createElement('div');
    wrapper.appendChild(show_row(node, level));

    const children = vtypes.filter(t => t.parent_id == node.id);
    children.forEach(child => {
        wrapper.appendChild(build_tree(child, level+1));
    });
    return wrapper;
}

function show_row(type, level) {
    let div = document.createElement('div');
    div.className = "row align-items-center";
    div.style.marginBottom = "10px";
    div.style.padding = "10px";
    div.style.marginLeft = (level * 20) + "px"; // <-- сміщення

    // кольори для рівнів
    const colors = ["#d1e7dd", "#cff4fc", "#f8d7da", "#fff3cd", "#e2e3e5"];
    div.style.backgroundColor = colors[level % colors.length];

    div.innerHTML = `
        <div class="col-md-3">
            <h6>${type.name}</h6>
        </div>
        <div class="col-md-4"><p>${type.description ?? ''}</p></div>
        <div class="col-md-1"><p>${type.slug ?? ''}</p></div>
        <div class="col-md-2">
            ${type.icon ? `<img src="/storage/types/${type.icon}" style="width:100px;height:100px;">`
                        : `<div style="width:100px;height:100px;background:#eaeaea;display:flex;align-items:center;justify-content:center;">No Image</div>`}
        </div>
        @if(Auth::user()->hasRole('admin'))
        <div class="col-md-2 d-flex flex-column">
            <a class="btn btn-success w-100" href="/types/${type.id}">{{__('View')}}</a>
            <a class="btn btn-warning w-100" href="/types/${type.id}/edit">{{__('Edit')}}</a>
            <form method="POST" action="/types/${type.id}" style="margin:0;">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <input type="hidden" name="_method" value="DELETE">
                <button type="submit" class="btn btn-danger w-100">{{__('Delete')}}</button>
            </form>
            <a class="btn btn-info w-100" href="/types/create?parent_id=${type.id}">{{__('Create Subtype')}}</a>
        </div>
        @endif
    `;
    return div;
}

</script>
@endsection
