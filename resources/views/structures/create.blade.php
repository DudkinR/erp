@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h1>{{__('Create position in structure')}}</h1>
                <form method="POST" action="{{ route('structure.store') }}">
                     @csrf
                    <div class="form-group">
                        <label for="name"> {{__('Name')}}</label>
                        <input type="text" class="form-control" id="name" name="name">
                    </div>
                    <div class="form-group">
                        <label for="description">
                            {{__('Description')}}
                        </label>                            
                        <textarea class="form-control" id="description" name="description"></textarea>
                    </div>
                    <div class="form-group">
                        <label for="parent_id">
                            {{__('Parent')}}
                        </label>
                        <?php
                            $structures = \App\Models\Struct::orderBy('name')->get();
                          
                        ?>
                        <select class="form-control" id="parent_id" name="parent_id">
                            <option value="0">{{__('Select parent')}}</option>
                            @foreach($structures as $structure)
                                <option value="{{$structure->id}}">{{$structure->name}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="kod">
                            {{__('Kod in accounting')}}
                        </label>
                        <input type="text" class="form-control" value="0" id="kod" name="kod">
                    </div>
                    <div class="form-group">
                        <label for="slug">
                            {{__('ABV')}}
                        </label>
                        <input type="text" class="form-control" id="slug" name="slug">
                    </div>
                    <div class="form-group">
                        <label for="status">
                            {{__('Status')}}
                        </label>
                        <select class="form-control" id="status" name="status">
                            <option value="active">{{__('Active')}}</option>
                            <option value="inactive">{{__('Inactive')}}</option>
                            <option value="deleted">{{__('Deleted')}}</option>
                            <option value="draft">{{__('Draft')}}</option>

                        </select>
                    </div>
                    <div class="form-group">
                        <label for="positions">
                            {{__('Positions')}}
                        </label>
                        <select class="form-control" id="positions" name="positions[]" multiple>
                            @foreach($positions as $position)
                                <option value="{{$position->id}}">{{$position->name}}</option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary">Create</button>
                </form>
            </div>
        </div>
    </div>
    <script>
        const positions = @json($positions);
        // привыборе позиции заполнять этими данными
        const positionsSelect = document.getElementById('positions');
        const nameInput = document.getElementById('name');
        const descriptionInput = document.getElementById('description');
        positionsSelect.addEventListener('change', function() {
            const selectedPositions = Array.from(this.selectedOptions).map(option => option.value);
            const selectedPositionsData = positions.filter(position => selectedPositions.includes(position.id.toString()));
            console.log(selectedPositionsData);
            nameInput.value = selectedPositionsData.map(position => position.name).join(', ');
            descriptionInput.value = selectedPositionsData.map(position => position.description).join(', ');

        });
    </script>
@endsection