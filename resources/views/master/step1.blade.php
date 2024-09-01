@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                
                <a class="text-right" href="{{ route('master.index') }}">{{__('Back')}}</a>
                
                <div class="container mt-4">
                    <div class="card border-info">
                        <div class="card-header bg-info text-white">
                            <h2 class="card-title">{{ __('Analysis of Work') }}</h2>
                        </div>
                        <div class="card-body">
                            <h4 class="mb-3"><strong>{{ __('Task') }}:</strong> {{ $master->text }}</h4>
                
                            <h5>
                            @php if($master->urgency <= 3) {
                                $color = 'green';
                            } elseif($master->urgency <= 7) {
                                $color = 'yellow';
                            } else {
                                $color = 'red';
                            }
                            @endphp
                                <strong>{{ __('Urgency') }}:</strong>
                                <span class="badge" style="background-color: {{ $color }}">
                                    {{ $master->deadline }}
                                </span>
                            </h5>
                
                            <div class="mt-2">
                                <span class="badge bg-secondary">{{ __('Urgency Level') }}: </span>
                                <span class="badge" style="background-color: {{ $color }}">{{ $master->urgency }}</span>
                            </div>
                
                            <h5 class="mt-3">
                                <strong>{{ __('Basis') }}:</strong> 
                                <span class="text-primary">{{ $master->basis }}</span>
                            </h5>
                
                            <h5 class="mt-2">
                                <strong>{{ __('Who gave the task') }}:</strong> 
                                <span class="text-success">{{ $master->who }}</span>
                            </h5>
                
                            <h5 class="mt-2">
                                <strong>{{ __('Comment') }}:</strong> 
                                <p class="text-muted">{{ $master->comment }}</p>
                            </h5>
                        </div>
                    </div>
                </div>
                
                <form method="POST" action="{{ route('master.step2', $master->id) }}" class="p-3 border rounded shadow-sm bg-light">
                    @csrf
                    <input type="hidden" name="master_id" value="{{ $master->id }}">
                
                    <!-- Planned Time -->
                    <div class="form-group d-flex align-items-center gap-3 mb-4">
                        <label for="estimate" class="form-label fw-semibold">{{ __('Planned Time') }}</label>
                        <input type="number" class="form-control form-control-sm w-auto" id="estimate" name="estimate" value="{{ $master->estimate }}" min="1" max="100">
                        <span class="text-muted">{{ __('hours') }}</span>
                    </div>
                
                    <!-- Workers and Personals -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="workers">{{ __('Workers') }}</label>
                                <small class="d-block text-muted"><b>({{ __('Select the workers involved in the task') }})</b></small>
                                <select class="form-control" id="workers" name="workers[]" size="5" multiple>
                                    @foreach ($master->personals as $personal)
                                        <option value="{{ $personal->id }}" selected>{{ $personal->fio }}</option>
                                    @endforeach
                                </select>
                                <button type="button" class="btn btn-sm btn-outline-secondary mt-2" id="clear_workers">{{ __('Clear') }}</button>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="personals">{{ __('Personals') }}</label>
                                <small class="d-block text-muted"><b>({{ __('Available personals for assignment') }})</b></small>
                                <select class="form-control" id="personals" size="5">
                                    <option value="" selected>{{ __('Select') }}</option>
                                    @foreach ($personals as $personal)
                                        <option value="{{ $personal->id }}">{{ $personal->fio }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                
                    <!-- Docs -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="docs">{{ __('Docs') }}</label>
                                <small class="d-block text-muted"><b>({{ __('Documents associated with the task') }})</b></small>
                                <select class="form-control" id="docs" name="docs[]" size="5" multiple>
                                    @foreach ($master->docs as $document)
                                        <option value="{{ $document->id }}" selected>{{ $document->name }}</option>
                                    @endforeach
                                </select>
                                <button type="button" class="btn btn-sm btn-outline-secondary mt-2" id="clear_docs">{{ __('Clear') }}</button>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="documents">{{ __('Select Documents') }}</label>
                                <small class="d-block text-muted"><b>({{ __('Choose additional documents if needed') }})</b></small>
                                <select class="form-control" id="documents" size="5">
                                    <option value="" selected>{{ __('Select') }}</option>
                                    @foreach ($docs as $document)
                                        <option value="{{ $document->id }}">{{ $document->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                
                    <!-- Resources -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="resource">{{ __('Planned Resource') }}</label>
                                <small class="d-block text-muted"><b>({{ __('Resources planned for the task') }})</b></small>
                                <select class="form-control" id="resource" name="resource[]" size="5" multiple>
                                    @foreach ($master->resources as $resource)
                                        <option value="{{ $resource->id }}" selected>{{ $resource->name }}</option>
                                    @endforeach
                                </select>
                                <button type="button" class="btn btn-sm btn-outline-secondary mt-2" id="clear_resource">{{ __('Clear') }}</button>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="my_resource">{{ __('My Resource') }}</label>
                                <small class="d-block text-muted"><b>({{ __('Choose or add your resources') }})</b></small>
                                <select class="form-control" id="my_resource" name="my_resource" size="5">
                                    <option value="" selected>{{ __('Select') }}</option>
                                    @foreach ($resources as $resource)
                                        <option value="{{ $resource->id }}">{{ $resource->name }}</option>
                                    @endforeach
                                </select>
                                <label class="mt-3">{{ __('New Resource') }}</label>
                                <input type="text" class="form-control form-control-sm mb-2" id="new_resource" name="new_resource" value="{{ old('new_resource') }}">
                                <button type="button" class="btn btn-sm btn-primary" id="add_resource">{{ __('Add') }}</button>
                            </div>
                        </div>
                    </div>
                
                    <!-- Submit and Cancel Buttons -->
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">{{ __('Next') }}</button>
                        <a href="{{ route('master.index') }}" class="btn btn-secondary">{{ __('Cancel') }}</a>
                    </div>
                </form>
                
            </div>
        </div>
    </div>
    <script>
        document.getElementById('clear_workers').addEventListener('click', function() {
        // перекинуть все элементы из workers в personals
            var workers = document.getElementById('workers');
            var personals = document.getElementById('personals');
            for (var i = 0; i < workers.options.length; i++) {
                var option = document.createElement('option');
                option.value = workers.options[i].value;
                option.text = workers.options[i].text;
                personals.add(option);
            }
            document.getElementById('workers').innerHTML = '';
        });
        document.getElementById('personals').addEventListener('change', function() {
            var workers = document.getElementById('workers');
            var option = document.createElement('option');
            option.value = this.value;
            option.text = this.options[this.selectedIndex].text;
            workers.add(option);
            // выбрать(отметить) все позиции в workers

            for (var i = 0; i < workers.options.length; i++) {                
                workers.options[i].selected = true;
            }
            //  удалить выбранный элемент из списка
            var select = document.getElementById('personals');
            select.remove(select.selectedIndex);
            
            this.value = '';
        });
        document.getElementById('clear_docs').addEventListener('click', function() {
            // перекинуть все элементы из docs в documents
            var docs = document.getElementById('docs');
            var documents = document.getElementById('documents');
             for (var i = 0; i < docs.options.length; i++) {
                var option = document.createElement('option');
                option.value = docs.options[i].value;
                option.text = docs.options[i].text;
                documents.add(option);
            }
            document.getElementById('docs').innerHTML = '';
        });
        document.getElementById('documents').addEventListener('change', function() {
            var docs = document.getElementById('docs');
            var option = document.createElement('option');
            option.value = this.value;
            option.text = this.options[this.selectedIndex].text;
            docs.add(option);
            // выбрать(отметить) все позиции в 
            for (var i = 0; i < docs.options.length; i++) {
                docs.options[i].selected = true;
            }
            //  удалить выбранный элемент из списка
            var select = document.getElementById('documents');
            select.remove(select.selectedIndex);
            this.value = '';
        });
        document.getElementById('clear_resource').addEventListener('click', function() {
            // перекинуть все элементы из resource в my_resource
            var resource = document.getElementById('resource');
            var my_resource = document.getElementById('my_resource');
            for (var i = 0; i < resource.options.length; i++) {
                var option = document.createElement('option');
                option.value = resource.options[i].value;
                option.text = resource.options[i].text;
                my_resource.add(option);
            }
            document.getElementById('resource').innerHTML = '';
        });
        document.getElementById('add_resource').addEventListener('click', function() {
            var resource = document.getElementById('resource');
            var option = document.createElement('option');
            var new_resource= document.getElementById('new_resource');
            option.value = new_resource.value; 
            option.text = new_resource.value;
            resource.add(option);
              // выбрать(отметить) все позиции в resource
            for (var i = 0; i < resource.options.length; i++) {
                resource.options[i].selected = true;
            }
            //  удалить выбранный элемент из списка

            document.getElementById('new_resource').value = '';
        });
        // add new resource to select list value = text
        document.getElementById('my_resource').addEventListener('change', function() {
            var resource = document.getElementById('resource');
            var option = document.createElement('option');
            option.value = this.value;  
            option.text = this.options[this.selectedIndex].text;
            resource.add(option);
            // выбрать(отметить) все позиции в resource
            for (var i = 0; i < resource.options.length; i++) {
                resource.options[i].selected = true;
            }
            //  удалить выбранный элемент из списка
            var select = document.getElementById('my_resource');
            select.remove(select.selectedIndex);
            this.value = '';

        });


    </script>
@endsection