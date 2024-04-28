@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
            <h1>{{__('nomenclatures')}}</h1>
                <a class="text-right" href="{{ route('nomenclaturs.create') }}">{{__('Create')}}</a>
            </div>
        </div> 
        <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    <input type="text" class="form-control" id="search" name="search" placeholder="{{__('Search')}}" onkeyup="findWords()">     </div>                   
            </div>
        </div>
        <div class="row">
            <div class="col-md-1">
              <b>  {{__('Id')}}</b>
            </div>
            <div class="col-md-4">
                <b>   {{__('Name')}}</b>
            </div>
            <div class="col-md-2">
                <b>    {{__('Description')}}</b>
            </div>
            <div class="col-md-3">
                <b>   {{__('Type')}}</b>
            </div>
            <div class="col-md-2">
                <b>   {{__('Actions')}}</b>
            </div>
        </div>    
        <div class="container" id="numenclatures">
        </div>   
    </div>
    <script>
    const nomenclatures = @json($nomenclatures);  
    <?php $types = \App\Models\Type::all(); ?>  
    const types = @json($types);
    const div_numenclatures = document.getElementById('numenclatures');
    var NMS = nomenclatures;
    function renderNomenclatures() {
        div_numenclatures.innerHTML = '';
        NMS.forEach(nomenclature => {
            const div = document.createElement('div');
            div.innerHTML = `
              <div class ="row border">
                <div class="col-md-1">
                    ${nomenclature.id}
                </div>
                <div class="col-md-4">
                    ${nomenclature.name}
                </div>
                <div class="col-md-2">
                    ${nomenclature.description}
                </div>
                <div class="col-md-3">
                    ${types.find(type => type.id === nomenclature.type_id).name}
                </div>
                <div class="col-md-2">
                    <a href="{{ route('nomenclaturs.edit', '') }}/${nomenclature.id}">{{__('Edit')}}</a>
                    <a href="{{ route('nomenclaturs.destroy', '') }}/${nomenclature.id}">{{__('Delete')}}</a>
                    <a href="{{ route('nomenclaturs.show', '') }}/${nomenclature.id}">{{__('Show')}}</a>
                    </div>
                </div>
            `;          
            div_numenclatures.appendChild(div);
        });
    }
    renderNomenclatures();
    function findWords() {
        const search = document.getElementById('search').value.toLowerCase(); // Convert to lowercase for case-insensitive search
        NMS = nomenclatures.filter(nomenclature => {
        // Check if nomenclature.name and nomenclature.description are not null before calling toLowerCase()
        const nameMatches = nomenclature.name && nomenclature.name.toLowerCase().includes(search);
        const descriptionMatches = nomenclature.description && nomenclature.description.toLowerCase().includes(search);
        return nameMatches || descriptionMatches;
         });
         console.log(NMS);
        renderNomenclatures(); // Render the filtered nomenclatures
    }
            
           
    </script>
@endsection