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
                <h1>{{__('Event new')}}</h1>
                <form method="POST" action="{{ route('events.store') }}">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <div class="form-group">
                        <label for="name">{{__('Name of event')}}</label>
                        <input type="text" class="form-control" id="name" name="name" value="@if(isset($copy_event)){{ $copy_event->name }}@else{{ old('name') }}@endif">
                    </div>
                    <div class="form-group">
                        <label for="description">{{__('Description of event')}}</label>
                        <textarea class="form-control" rows=5 id="description" name="description">@if(isset($copy_event)){{ $copy_event->description }}@else{{ old('description') }}@endif</textarea>
                    </div>
                    <div class="form-group">
                        <label for="start_date">{{__('Start Date of event')}}</label>
                        <input type="date" class="form-control" id="start_date" name="start_date" value="@if(isset($copy_event)){{ old('start_date') }} @else{{ old('start_date') }}@endif">
                    </div>
                    <div class="form-group">
                        <label for="end_date">{{__('End Date of event')}}</label>
                        <input type="date" class="form-control" id="end_date" name="end_date" value="@if(isset($copy_event)){{ $copy_event->end_date }}@else{{ old('end_date') }}@endif">
                    </div>
                    <div class="form-group">
                        <label for="status">{{__('Status of event')}}  %</label>
                        <input type="number" class="form-control" id="status" name="status" value="{{ $copy_event->status ?? 0 }}">
                    </div>
                    <div class="form-group">
                        <label for="control_date">{{__('Control Date of event')}}</label>
                        <input type="date" class="form-control" id="control_date" name="control_date" value="@if(isset($copy_event)){{ $copy_event->control_date }}@else{{ old('control_date') }}@endif">
                    </div>
                    @php
                    $projects = App\Models\Project::all();
                    $steps = App\Models\Step::all();
                    $divisions = App\Models\Division::orderBy('name', 'asc')->get();    
                    @endphp
                    <div class="form-group">
                        <label for="projects_id">{{__('Projects of event')}}</label>
                        <select name="projects_id[]" id="projects_id" class="form-control" multiple>
                            @foreach($projects as $project)
                                <option value="{{ $project->id }}"
                                    @if(isset($copy_event) && $copy_event->projects->contains($project->id)) selected @endif
                                    >{{ $project->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="steps_id">{{__('Steps of event')}}</label>
                        <select name="steps_id[]" id="steps_id" class="form-control" multiple>
                            @foreach($steps as $step)
                                <option value="{{ $step->id }}"
                                    @if(isset($copy_event) && $copy_event->steps->contains($step->id)) selected @endif
                                    >{{ $step->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="divisions_id">{{__('Divisions of event')}}</label>
                        <select name="divisions_id[]" id="divisions_id" class="form-control" multiple>
                            @foreach($divisions as $division)
                                <option value="{{ $division->id }}"
                                    >{{ $division->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="positions_id">{{__('Positions of event')}}</label>
                        <select name="positions_id[]" id="positions_id" class="form-control" multiple>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary w-100" >{{__('Create')}}</button>
                </form>
            </div>
        </div>
    </div>


    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var divisionsSelect = document.getElementById('divisions_id');
            var positionsSelect = document.getElementById('positions_id');
    
            divisionsSelect.addEventListener('change', function () {
                var selectedDivisions = Array.from(divisionsSelect.selectedOptions).map(option => option.value);
    
                fetch('{{ route('get_position_from_divisions_api') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'  // Laravel CSRF защита
                    },
                    body: JSON.stringify({
                        divisions_id: selectedDivisions
                    })
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    console.log('Positions:', data);
                    // Очистка текущих опций
                    positionsSelect.innerHTML = '';
    
                    // Добавляем новые опции на основе ответа
                    data.forEach(position => {
                        var option = document.createElement('option');
                        option.value = position.id;
                        option.textContent = position.name;
                        positionsSelect.appendChild(option);
                    });
                })
                .catch(error => {
                    console.error('Error fetching positions:', error);
                });
            });
        });
    </script>
@endsection