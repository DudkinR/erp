@extends('layouts.app')
@section('content')
<style>
    /* The Modal (background) */
    .modal {
        display: none; /* Hidden by default */
        position: fixed; /* Stay in place */
        z-index: 1; /* Sit on top */
        left: 0;
        top: 0;
        width: 100%; /* Full width */
        height: 100%; /* Full height */
        overflow: auto; /* Enable scroll if needed */
        background-color: rgb(0,0,0); /* Fallback color */
        background-color: rgba(0,0,0,0.4); /* Black w/ opacity */
        padding-top: 60px;
    }
    
    /* Modal Content */
    .modal-content {
        background-color: #fefefe;
        margin: 5% auto; /* 15% from the top and centered */
        padding: 20px;
        border: 1px solid #888;
        width: 80%; /* Could be more or less, depending on screen size */
        border-radius: 10px;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
        position: relative;
    }
    
    /* The Close Button */
    .close {
        color: #aaa;
        float: right;
        font-size: 28px;
        font-weight: bold;
    }
    
    .close:hover,
    .close:focus {
        color: black;
        text-decoration: none;
        cursor: pointer;
    }
    
    .modal h2, .modal p, .modal h3 {
        margin: 10px 0;
    }
    
    .modal select {
        width: 100%;
        padding: 10px;
        margin: 10px 0;
    }
    
    .modal button {
        padding: 10px 20px;
        background-color: #4CAF50;
        color: white;
        border: none;
        cursor: pointer;
        border-radius: 5px;
        margin-top: 10px;
    }
    
    .modal button:hover {
        background-color: #45a049;
    }
    </style>
    
    <div class="container">
        @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif
        @if(isset($goal))
            <div class="row">
                <div class="col-md-12">
                    <h1>{{ $goal->name }}</h1>
                    <p>{{ $goal->description }}</p>
                    <p>{{ __('Due Date') }}: {{ $goal->due_date }}</p>
                    <p>{{ __('Status') }}: 
                        @if($goal->status == '0')
                            {{ __('Not Started') }}
                        @elseif($goal->status == '1')
                            {{ __('In Progress') }}
                        @elseif($goal->status == '2')
                            {{ __('Complete') }}
                        @endif  
                    </p>
                    @if($goal->status == '2')
                        <p>{{ __('Completed On') }}: {{ $goal->completed_on }}</p>
                    @endif
                    @if(Auth::user()->hasRole('quality-engineer','admin'))
                    <a href="{{ route('goals.edit', $goal->id) }}" class="btn btn-warning">{{ __('Edit') }}</a>
                    <form style="display:inline-block" method="POST" action="{{ route('goals.destroy', $goal->id) }}">
                        @method('DELETE')
                        @csrf
                        <button type="submit" class="form-control btn btn-danger">{{ __('Delete') }}</button>
                    </form>
                    @endif
                </div>
            </div>
        @endif
        <div class="row">
            <div class="col-md-12">
                <h1>{{ __('Find') }}</h1>
                <input type="text" id="search"  class="form-control"  placeholder="{{ __('Search') }}">
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <h1>{{ __('Functions') }}</h1>
                @if(Auth::user()->hasRole('quality-engineer','admin'))
                <a class="btn btn-info w-100" href="{{ route('funs.create') }}">{{ __('Create Function') }}</a>
                @endif
                <table class="table" id="table_funs">
                    <thead>
                        <tr>
                            <th>{{ __('Title') }}</th>
                            <th>{{ __('Description') }}</th>
                            <th>{{ __('Actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($funs as $funct)
                            <tr>
                                <td id="funct_{{$funct->id}}"
                                class = "@if($funct->positions->count() == 0)
                                    bg-warning
                                    @endif
                                    ">
                                    {{ $funct->name }}
                                    <hr>
                                    <h5>{{ __('Positions') }}:</h5>
                                    <hr>
                                    <ul>
                                    @foreach($funct->positions as $position)
                                        <li>{{ $position->name }}</li>
                                    @endforeach
                                </ul>
                                    <button onclick="GenereteModal({{ $funct->id }})">Add Positions</button>
                                   
                                </td>
                                <td>
                                    <p>
                                    {{ $funct->description }}
                                </p>
                                <p>
                                   <h6> {{__('Goals')}}:</h6>
                                    <ul>
                                    @foreach($funct->goals as $goal)
                                        <li>{{ $goal->name }} </li>
                                    @endforeach
                                    </ul>

                                <p>
                                <h6> {{ __('Objective') }}: </h6>
                                    <ul>
                                    @foreach($funct->objectives as $objective)
                                        <li>{{ $objective->name }} </li>
                                    @endforeach
                                    </ul>
                                </p>

                                </td>
                                <td>
                                    <a href="{{ route('funs.show', $funct->id) }}" class="btn btn-default">{{ __('View') }}</a>
                                    @if(Auth::user()->hasRole('quality-engineer','admin'))
                                    <a href="{{ route('funs.edit', $funct->id) }}" class="btn btn-warning">{{ __('Edit') }}</a>
                                    <form style="display:inline-block" method="POST" action="{{ route('funs.destroy', $funct->id) }}">
                                        @method('DELETE')
                                        @csrf
                                        <button type="submit" class="form-control btn btn-danger">{{ __('Delete') }}</button>
                                    </form>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div id="myModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <div id="modal-body"></div>
        </div>
    </div>
    
    <script>
    const funs = @json($funs);
    var TF=funs;
    const positions = @json($positions);
    
    document.addEventListener('DOMContentLoaded', () => {
        const modal = document.getElementById('myModal');
        const closeBtn = document.querySelector('.close');
    
        closeBtn.onclick = () => {
            modal.style.display = "none";
        };
    
        window.onclick = (event) => {
            if (event.target == modal) {
                modal.style.display = "none";
            }
        };
    });
    
    function savePositions(functId) {
        var url = '{{ route('funs.store_positions_api') }}';
        var csrf_token = document.querySelector('input[name="_token"]').value;
        var selectedPositions = Array.from(document.querySelectorAll('#positions option:checked')).map(option => option.value);
    
        fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrf_token
            },
            body: JSON.stringify({
                fun_id: functId,
                positions: selectedPositions
            })
        })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                console.log('Success:', data);
                document.getElementById('funct_' + functId).classList.remove('bg-danger');
                document.getElementById('myModal').style.display = "none";
                // reload page
                location.reload();
            })
            .catch((error) => {
                console.error('Error:', error);
            });
    }
    
    function GenereteModal(functId) {
        var modal = document.getElementById('myModal');
        const funct = funs.find(funct => funct.id == functId);
        const My_positions = funct.positions;
    
        var modalContent = `
            <h2>${funct.name}</h2>
            <p>${funct.description}</p>
            <h3>Positions</h3>
            <select id="positions" multiple size="5">`;
        positions.forEach(position => {
            const isSelected = My_positions.some(pos => pos.id == position.id);
            modalContent += `<option value="${position.id}" ${isSelected ? 'selected' : ''}>${position.name}</option>`;
        });
        modalContent += `</select>
            <button onclick="savePositions(${functId})">Save</button>`;
        
        document.getElementById('modal-body').innerHTML = modalContent;
        modal.style.display = "block";
    }

    function form_table(){
        const table = document.getElementById('table_funs');
        // title table
        table.innerHTML = `
        <thead>
            <tr>
                <th>{{ __('Title') }}</th>
                <th>{{ __('Description') }}</th>
                <th>{{ __('Actions') }}</th>
            </tr>
        </thead>
        <tbody>
        `;
        // for each TF
        TF.forEach(funct => {
            table.innerHTML += `
            <tr>
                <td id="funct_${funct.id}"
                class = "${funct.positions.length == 0 ? 'bg-warning' : ''}
                    ">
                    ${funct.name}
                    <hr>
                    <h5>{{ __('Positions') }}:</h5>
                    <hr>
                    <ul>
                    ${funct.positions.map(pos => `<li>${pos.name}</li>`).join('')}
                </ul>
                  @if(Auth::user()->hasRole('quality-engineer','admin'))
                    <button onclick="GenereteModal(${funct.id})">{{ __('Add Positions') }}</button>
                    @endif
                   
                </td>
                <td>
                    <p>
                    ${funct.description}
                </p>
                <p>
                   <h6> {{__('Goals')}}:</h6>
                    <ul>
                    ${funct.goals.map(goal => `<li>${goal.name}</li>`).join('')}
                </ul>

                <p>
                <h6> {{ __('Objective') }}: </h6>
                    <ul>
                    ${funct.objectives.map(obj => `<li>${obj.name}</li>`).join('')}
                </ul>
                </p>

                </td>
                <td>
                    <a href="/funs/${funct.id}" class="btn btn-default">{{ __('View') }}</a>
                      @if(Auth::user()->hasRole('quality-engineer','admin'))
                    <a href="/funs/${funct.id}/edit" class="btn btn-warning">{{ __('Edit') }}</a>
                    <form style="display:inline-block" method="POST" action="/funs/${funct.id}/destroy">
                        @method('DELETE')
                        @csrf
                        <button type="submit" class="form-control btn btn-danger">{{ __('Delete') }}</button>
                    </form>
                    @endif
                </td>
            </tr>
            `;
        });
        table.innerHTML += `</tbody>`;

    }
    function search_mass(){
        var search = document.getElementById('search').value;
        // find in funs (name description positions goals objactives) write into TF
        TF = funs.filter(funct => {
            return funct.name.includes(search) || funct.description.includes(search) || funct.positions.some(pos => pos.name.includes(search)) || funct.goals.some(goal => goal.name.includes(search)) || funct.objectives.some(obj => obj.name.includes(search));
        });
        // rewrite table
        form_table();
    }
    form_table();
    document.getElementById('search').addEventListener('input', search_mass);


    </script>
    
    @endsection
    