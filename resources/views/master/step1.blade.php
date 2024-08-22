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
                   <div class="form-group" style="margin-top: 20px;">
                        <label for="workers">{{__('Workers')}}</label>
                        <select class="form-control" id="workers"  name="workers[]" size="5" multiple>
                           
                        </select>
                        <button type="button" class="btn btn-primary" id="clear_workers">{{__('Clear')}}</button>

                      </div>
                    <div class="form-group">
                        <label for="personals">{{__('Personals')}}</label>
                        <select class="form-control" id="personals" >
                            <option value="" selected>{{__('Select')}}</option>
                            @foreach ($personals as $personal)
                                <option value="{{ $personal->id }}">{{ $personal->fio }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group" style="margin-top: 20px;">
                        <label for="docs">{{__('Docs')}}</label>
                        <select class="form-control" id="docs"  name="docs[]" size="5" multiple>
                        </select>
                        <button type="button" class="btn btn-primary" id="clear_docs">{{__('Clear')}}</button>
                    </div>
                    <div class="form-group">
                        <label for="documents">{{__('Select Documents')}}</label>
                        <select class="form-control" id="documents" >
                            <option value="" selected>{{__('Select')}}</option>
                            @foreach ($docs as $document)
                                <option value="{{ $document->id }}">{{ $document->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="resource">{{__('Planed Resource')}}</label>
                        <select class="form-control" id="resource" name="resource[]" size="5" multiple>
                            
                        </select>
                        <button type="button" class="btn btn-primary" id="clear_resource">{{__('Clear')}}</button>

                    </div>
                    <div class="form-group"> 
                        <label for="my_resource">{{__('My Resource')}}</label>
                        <select class="form-control" id="my_resource" name="my_resource">
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

                    <div class="form-group">
                        <label for="estimate">{{__('Planed Time (h)')}}</label>
                        <input type="number" class="form-control" id="estimate" name="estimate" value="{{ old('estimate', 1) }}" min="1" max="100">
                    </div>

                    <button type="submit" class="btn btn-primary">{{__('Next')}}</button>
                    <a href="{{ route('master.index') }}" class="btn btn-secondary">{{__('Cancel')}}</a>
                </form>
            </div>
        </div>
    </div>
    <script>
        document.getElementById('clear_workers').addEventListener('click', function() {
            document.getElementById('workers').innerHTML = '';
        });
        document.getElementById('personals').addEventListener('change', function() {
            var workers = document.getElementById('workers');
            var option = document.createElement('option');
            option.value = this.value;
            option.text = this.options[this.selectedIndex].text;
            workers.add(option);
            this.value = '';
        });
        document.getElementById('clear_docs').addEventListener('click', function() {
            document.getElementById('docs').innerHTML = '';
        });
        document.getElementById('documents').addEventListener('change', function() {
            var docs = document.getElementById('docs');
            var option = document.createElement('option');
            option.value = this.value;
            option.text = this.options[this.selectedIndex].text;
            docs.add(option);
            this.value = '';
        });
        document.getElementById('clear_resource').addEventListener('click', function() {
            document.getElementById('resource').innerHTML = '';
        });
        document.getElementById('add_resource').addEventListener('click', function() {
            var resource = document.getElementById('resource');
            var option = document.createElement('option');
            option.value = document.getElementById('my_resource').value;
            option.text = document.getElementById('my_resource').options[document.getElementById('my_resource').selectedIndex].text;
            resource.add(option);
            document.getElementById('my_resource').value = '';
            document.getElementById('new_resource').value = '';
        });
        // add new resource to select list value = text
        document.getElementById('my_resource').addEventListener('change', function() {
            document.getElementById('new_resource').value = this.options[this.selectedIndex].text;
            // clear new resource field
            this.value = '';

        });


    </script>
@endsection