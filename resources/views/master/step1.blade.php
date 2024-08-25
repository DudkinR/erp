@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                
                <a class="text-right" href="{{ route('master.index') }}">{{__('Back')}}</a>
                
                <h2>{{__('Analize of work')}}</h2>
                <h3>{{__('Task')}}: {{ $master->text }}</h3>
                @php $color = $master->urgency > 5 ? 'red' : ($master->urgency > 3 ? 'orange' : 'green') @endphp
                <h3>{{__('Urgency')}}: <span style="color: {{ $color }}">{{ $master->deadline }}</span></h3>
                <h3>{{__('Basis')}}: {{ $master->basis }}</h3>
                <h3>{{__('Who give task')}}: {{ $master->who }}</h3>
                <h3>{{__('Comment')}}: {{ $master->comment }}</h3>
                <form method="POST" action="{{ route('master.step2', $master->id) }}">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <input type="hidden" name="master_id" value="{{ $master->id }}">
                    <div class="form-group">
                        <label for="estimate">{{__('Planed Time (h)')}}</label>
                        <input type="number" class="form-control" id="estimate" name="estimate" value="{{$master->estimate}}" min="1" max="100">
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group" style="margin-top: 20px;">
                                <label for="workers">{{__('Workers')}}</label>
                                <select class="form-control" id="workers"  name="workers[]" size="5" multiple>
                                    @foreach ($master->personals as $personal)
                                        <option value="{{ $personal->id }}" selected>{{ $personal->fio }}</option>
                                    @endforeach                           
                                </select>
                                <button type="button" class="btn btn-primary" id="clear_workers">{{__('Clear')}}</button>
                                </div>
                        </div>
                        <div class="col-md-6">
                             <div class="form-group">
                            <label for="personals">{{__('Personals')}}</label>
                            <select class="form-control" id="personals"  size="5">
                                <option value="" selected>{{__('Select')}}</option>
                                @foreach ($personals as $personal)
                                    <option value="{{ $personal->id }}">{{ $personal->fio }}</option>
                                @endforeach
                            </select>
                            </div>
                        </div>

                    </div>
                  
                    <div class="row">
                        <div class="col-md-6">
                    <div class="form-group" style="margin-top: 20px;">
                        <label for="docs">{{__('Docs')}}</label>
                        <select class="form-control" id="docs"  name="docs[]" size="5" multiple>
                            @foreach ($master->docs as $document)
                                <option value="{{ $document->id }}" selected>{{ $document->name }}</option>
                            @endforeach
                        </select>
                        <button type="button" class="btn btn-primary" id="clear_docs">{{__('Clear')}}</button>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="documents">{{__('Select Documents')}}</label>
                        <select class="form-control" id="documents" size="5">
                            <option value="" selected>{{__('Select')}}</option>
                            @foreach ($docs as $document)
                                <option value="{{ $document->id }}">{{ $document->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div> 
            
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="resource">{{__('Planed Resource')}}</label>
                        <select class="form-control" id="resource" name="resource[]" size="5" multiple>
                            
                            @foreach ($master->resources as $resource)
                                <option value="{{ $resource->id }}" selected>{{ $resource->name }}</option>
                            @endforeach
                        </select>
                        <button type="button" class="btn btn-primary" id="clear_resource">{{__('Clear')}}</button>

                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group"> 
                        <label for="my_resource">{{__('My Resource')}}</label>
                        <select class="form-control" id="my_resource" name="my_resource" size="5">
                            <option value="" selected>{{__('Select')}}</option>
                            @foreach ($resources as $resource)
                                <option value="{{ $resource->id }}">{{ $resource->name }}</option>
                            @endforeach
                        </select>
                        <h3>
                            {{__('New Resource')}}
                            <input type="text" class="form-control" id="new_resource" name="new_resource" value="{{ old('new_resource') }}">
                            <button type="button" class="btn btn-primary" id="add_resource">{{__('Add')}}</button>
                        </h3>
                    </div>
                </div>
            </div>

               
                    <button type="submit" class="btn btn-primary">{{__('Next')}}</button>
                    <a href="{{ route('master.index') }}" class="btn btn-secondary">{{__('Cancel')}}</a>
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