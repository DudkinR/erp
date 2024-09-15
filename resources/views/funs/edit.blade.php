@extends('layouts.app')
@section('content')
    <div class="container">
               @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        @if(session('success'))
        <div class="alert alert-success">{{ __(session('success')) }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ __(session('error')) }}</div>
    @endif
        <div class="row">
            <div class="col-md-12">
                <h1> {{__('Edit Function')}}</h1>
                <form method="POST" action="{{ route('funs.update',$fun) }}">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <input type="hidden" name="_method" value="PUT">
                    <div class="form-group">
                        <label for="title"> {{__('Title')}}</label>
                        <input type="text" class="form-control" id="name" name="name" value="{{ $fun->name }}">
                    </div>
                    <div class="form-group">
                        <label for="description"> {{__('Description')}}</label>
                        <textarea class="form-control" id="description" name="description">{!! $fun->description !!} </textarea>
                    </div>
                    <div class="form-group">
                        <label for="goals"> {{__('Goals')}}</label>
                        <select class="form-control" id="goals" name="goals[]" multiple size = 5>
                            @foreach($goals as $goal)
                                <option value="{{ $goal->id }}" 
                                @if(in_array($goal->id, $fun->goals->pluck('id')->toArray())) 
                                    selected
                                @endif
                                >{{ $goal->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <a href="{{route('goals.create')}}" class="btn btn-info w-100" >{{__('Add Goal')}}</a>
                        <hr>
                    </div>
                    <div class="form-group">
                        <label for="divisions"> {{__('Divisions')}}</label>
                        <select class="form-control" id = "divisions" name="divisions[]" multiple size = 5>
                            <option value="0">{{__('Not')}}</option>
                            @foreach($divisions as $division)
                                <option value="{{ $division->id }}" 
                                @if(in_array($division->id, $fun->positions->pluck('division_id')->toArray()))
                                    selected
                                @endif
                                >{{ $division->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="row">
                    <div class="form-group col-md-10">
                        <label for="positions"> {{__('Positions')}}</label>
                        <select class="form-control" id = "positions" name="positions[]" multiple size = 5>
                            <option value="0">{{__('Not')}}</option>
                            @foreach($positions as $position)
                                <option value="{{ $position->id }}" 
                                @if(in_array($position->id, $fun->positions->pluck('id')->toArray())) 
                                    selected
                                @endif
                                >{{ $position->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group col-md-2">
                        <a class="btn btn-warning w-100" 
                        id="reset_positions"
                        style = " visibility: hidden;"
                        >{{__('Reset')}}</a>
                        
                    </div>
                    <div class="form-group">
                        <a href="{{route('personal.create')}}" class="btn btn-info w-100" >{{__('Add Position')}}</a>
                        <hr>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">
                        {{__('Update')}}
                    </button>
                </form>
            </div>
        </div>
    </div>
    <script>
        const divisions = @json($divisions);
        const positions = @json($positions);
         document.getElementById('divisions').addEventListener('change', function(){
            const divisionId = this.value;
            const positionsSelect = document.getElementById('positions');
            positionsSelect.innerHTML = '';
            positions.forEach(position => {
                if (position.divisions.find(division => division.id == divisionId)) {
                    const option = document.createElement('option');
                    option.value = position.id;
                    option.innerText = position.name;
                    positionsSelect.appendChild(option);
                }
            });
            document.getElementById('reset_positions').style.visibility = 'visible';
        });
        // сбросить список должностей
        document.getElementById('reset_positions').addEventListener('click', function(){
            const positionsSelect = document.getElementById('positions');
            positionsSelect.innerHTML = '';
            positions.forEach(position => {
                const option = document.createElement('option');
                option.value = position.id;
                option.innerText = position.name;
                positionsSelect.appendChild(option);
            });
            this.style.visibility = 'hidden';
        });
    </script>
@endsection