@extends('layouts.app')
@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <a class="btn btn-info w-100" href="{{ route('objectives.index') }}">
                {{ __('Back to Objectives') }}
            </a>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">{{ __('Objective') }}</div>
                <div class="card-body">
                    @if($objective->parent!==null && $objective->parent->count()>0)
                    <h1>
                        {{ __('Parent') }} :
                        {{ $parent->first()->name }}
                    </h1>
                    @endif
                    <h2>{{ $objective->name }}</h2>
                    <p>{{ $objective->description }}</p>
                    <p id="goals"></p>
                    <p id="functs"></p>
                    <div class="bg-info">
                        <h1>{{ __('Add Function') }}</h1>
                        @php 
                            $fun_all = \App\Models\Fun::orderBy('id', 'desc')->get();
                        @endphp
                        <div class="row">
                        <div class="col-md-8">
                            
                            <select class="form-control" id="exist" name="exist" size = "5">
                            <option value="0">{{__('None')}}</option>
                            @foreach($fun_all as $fun)
                                <option value="{{ $fun->id }}">{{ $fun->name }}</option>
                            @endforeach
                            </select>
                        </div>
                        <div class="col-md-4"><label for="exist">{{__('Exists Function')}}</label>
                            @if(Auth::user()->hasRole('quality-engineer','admin'))    
                        <button type="button"
                            onclick="submitajax_exist(document.getElementById('exist').value);"
                        >{{__('Add')}}</button>
                        @endif
                        </div>
                        </div>
                        <div class="container border bg-info">
                            <div class="row">
                                <div class="col-md-12">
                                    <form id="functForm" method="POST" action="{{ route('funs.store_api') }}">
                                        @csrf
                                        <input type="hidden" id="objective_id" name="objective_id" value="{{ $objective->id }}">
                                        <div class="form-group">
                                            <label for="name">{{ __('Name') }}</label>
                                            <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}">
                                        </div>
                                        <div class="form-group">
                                            <label for="description">{{ __('Description') }}</label>
                                            <textarea class="form-control" id="description" rows="6" name="description">{{ old('description') }}</textarea>
                                        </div>
                                        @if(Auth::user()->hasRole('quality-engineer','admin'))
                                        <button type="button" class="btn btn-primary" onclick="submitajax();">{{ __('Create') }}</button>
                                        @endif
                                    </form>
                                    
                                </div>
                            </div>
                        </div>
                    </div>
                     @if(Auth::user()->hasRole('quality-engineer','admin'))
                    <a href="{{ route('objectives.edit', $objective) }}" class="btn btn-warning w-100">{{ __('Edit') }}</a>
                    <form method="POST" action="{{ route('objectives.destroy', $objective) }}">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-danger w-100" type="submit">{{ __('Delete') }}</button>
                    </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

   <script>
   var Ogoals = @json($objective->goals);
var Ofuncts = @json($objective->functs);
const all_functs = @json($fun_all);
function submitajax() {
    var name = document.getElementById('name').value;
    var description = document.getElementById('description').value;
    var objective_id = document.getElementById('objective_id').value;
    var csrf_token = document.querySelector('input[name="_token"]').value;
    var url = '{{ route('funs.store_api') }}';
    fetch(url, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrf_token
        },
        body: JSON.stringify({
            name: name,
            description: description,
            objective_id: objective_id
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
            add_funct(data.fun);
        })
        .catch((error) => {
            console.error('Error:', error);
        });

 
}
function submitajax_exist(fun_id){
    var objective_id = document.getElementById('objective_id').value;
    var csrf_token = document.querySelector('input[name="_token"]').value;
    var url = '{{ route('funs.store_api') }}';
    fetch(url, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrf_token
        },
        body: JSON.stringify({
            fun_id: fun_id,
            objective_id: objective_id
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
            add_funct(data.fun);
        })
        .catch((error) => {
            console.error('Error:', error);
        });
}

function add_funct(funct) {
    document.getElementById('name').value = '';
    document.getElementById('description').value = '';
    Ofuncts.push(funct);
    functs_form();
}

function next_id(functs) {
    return functs.length > 0 ? Math.max(...functs.map(f => f.id)) + 1 : 1;
}

function goals_form() {
    const goals = document.getElementById('goals');
    goals.innerHTML = '{{__("Goals")}}:' + Ogoals.length + '<br><ul>';
    Ogoals.forEach(function(goal) {
        goals.innerHTML += '<li>' + goal.name + '</li>';
    });
    goals.innerHTML += '</ul>';
}

function functs_form() {
    const functs = document.getElementById('functs');
    functs.innerHTML = '{{__("Functs")}}:' + Ofuncts.length + '<br><ul>';
    Ofuncts.forEach(function(funct) {
        functs.innerHTML += '<li>' + funct.name + '</li>';
    });
    functs.innerHTML += '</ul>';
    console.log(Ofuncts); 
}

goals_form();
functs_form();

   </script>
@endsection