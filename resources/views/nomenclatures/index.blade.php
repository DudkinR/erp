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
            <h1>{{__('Nomenclatures')}}</h1>
            @if(Auth::user()->hasRole('quality-engineer','admin'))
            
                <a class="text-right" href="{{ route('nomenclaturs.create') }}">{{__('Create')}}</a>
           @endif </div>
        </div> 
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <input type="text" class="form-control" id="search" name="search" placeholder="{{__('Search')}}" onkeyup="findWords()">     
                </div>                   
            </div>
            <div class="col-md-6">
                <div class="form-group"> 
                    @php $types = \App\Models\Type::orderBy('id', 'desc')->get(); @endphp
                    <select class="form-control" id="type" name="type" onchange="findWords()">
                        <option value="">{{__('All')}}</option>
                        @foreach($types as $type)
                            <option value="{{$type->id}}">{{$type->name}}</option>
                        @endforeach
                    </select>
                </div>
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
    <?php $types = \App\Models\Type::orderBy('id', 'desc')->get(); ?>  
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
                    ${nomenclature.types.map(type => type.name).join(', ')}

                </div>
                <div class="col-md-2">
                    <a href="nomenclaturs/${nomenclature.id}/edit">{{__('Edit')}}</a>
                    <a href="nomenclaturs/${nomenclature.id}/show">{{__('Show')}}</a>
                    <button onclick="addToProject(${nomenclature.id})">{{__('Add to project')}}</button>
                    </div>
                </div>
            `;          
            div_numenclatures.appendChild(div);
        });
    }

function findWords() {
        const search = document.getElementById('search').value.toLowerCase();
        const type = document.getElementById('type').value;
        fetch(`/search-nomenclatures?search=${search}&type=${type}`)
            .then(response => response.json())
            .then(data => {
             //   console.log(data); // For debugging
                NMS = data;
                renderNomenclatures();
            })
            .catch(error => console.error('Error:', error));
    }

        renderNomenclatures();
    @php 
        $Work_projects = \App\Models\Project::where('current_state', '!=' ,'Закритий')->get(); 
        $positions = \App\Models\Position::orderBy('id', 'desc')->get();
        $stages = \App\Models\Stage::orderBy('id', 'desc')->get();
        $steps = \App\Models\Step::orderBy('id', 'desc')->get();
    @endphp
    const Work_projects = @json($Work_projects);
    const positions = @json($positions);
    const stages = @json($stages);
    const steps = @json($steps);

    var project_id_value = {{ session('project_id') ?? 'null' }};
    var position_id_value = {{ session('position_id') ?? 'null' }};
    var quantity_value = {{ session('quantity') ?? 'null' }};
    var stage_id_value = {{ session('stage_id') ?? 'null' }};
    var step_id_value   = {{ session('step_id') ?? 'null' }};


    function addToProject(nomenclature_id) {
     // открываем всплывающее окно где выбираем проэкт из выпадающего списка и ставим количество отсылаем пост запросом на 
        // добавление в таблицу project_nomenclature
        const div = document.createElement('div');
        div.innerHTML = `
            <div class="modal" tabindex="-1" role="dialog" id="modal">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">{{__('Add to project')}}</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <select class="form-control" id="project_id">
                                ${Work_projects.map(project => `<option value="${project.id}"
                                    ${project.id === project_id_value ? 'selected' : ''}
                                >${project.name}</option>`).join('')}
                            </select>
                            <select class="form-control" id="position_id">
                                ${positions.map(position => `<option value="${position.id}"
                                    ${position.id === position_id_value ? 'selected' : ''}                                
                                >${position.name}</option>`).join('')}
                                   </select>
                            <input type="number" class="form-control" id="quantity" name="quantity"
                                value="${quantity_value}"
                             placeholder="{{__('Quantity')}}">
                            <select class="form-control" id="stage_id">
                                ${stages.map(stage => `<option value="${stage.id}"
                                    ${stage.id === stage_id_value ? 'selected' : ''}
                                >${stage.name}</option>`).join('')}
                            </select>
                            <select class="form-control" id="step_id">
                                ${steps.map(step => `<option value="${step.id}"
                                    ${step.id === step_id_value ? 'selected' : ''}
                                >${step.name}</option>`).join('')}
                            </select>
                         </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">{{__('Close')}}</button>
                            <button type="button" class="btn btn-primary" onclick="addNomenclatureToProject(${nomenclature_id})">{{__('Add')}}</button>
                        </div>
                    </div>
                </div>
            </div>
        `;
        document.body.appendChild(div);
        $('#modal').modal('show');
    }
    function addNomenclatureToProject(nomenclature_id) {
    const project_id = document.getElementById('project_id').value;
    const position_id = document.getElementById('position_id').value;
    const quantity = document.getElementById('quantity').value;
    const stage_name = document.getElementById('stage_id').value;
    const step_name = document.getElementById('step_id').value;
    project_id_value = project_id;
    position_id_value = position_id;
    quantity_value = quantity;
    stage_id_value = stage_name;
    step_id_value = step_name;

    var data = {
        nomenclature_id: nomenclature_id,
        project_id: project_id,
        position_id: position_id,
        quantity: quantity,
        stage_name: stage_name,
        step_name: step_name
    };

    fetch(`/add-nomenclature-to-project`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify(data)
    })
    .then(response => {
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.json();
    })
    .then(data => {
        console.log(data); // For debugging
        if (data.success) {
            // Assuming the server responds with { success: true } upon successful addition
            $('#modal').modal('hide');
        } else {
            // Handle possible server-side errors
            console.error('Error from server:', data.message);
        }
    })
    .catch(error => console.error('Error:', error));
}

    
    </script>
@endsection