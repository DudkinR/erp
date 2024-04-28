@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
            <h1>{{__('Show')}}</h1>
                <a class="text-right" href="{{ route('projects.index') }}">Back</a>
            </div>  
        </div>
        <div class="row">
            <div class="col-md-12">
                <h1>
                    <?php $client = App\Models\Client::find($project->client); ?>
                    {{ $client->name }}
                </h1>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        {{ $project->name }}
                    </div>
                    <div class="card-body">
                        <p>{{ $project->description }}</p>
                        <p>{{ $project->current_state }}</p>
                        <p>{{ $project->priority }}</p>
                        <p>{{ $project->start_date }}</p>
                        <p>{{ $project->end_date }}</p>
                    </div>
                    <div class="card-footer">
                        @foreach($project->stages as $stage)
                        <a href="{{route("stages.show",$stage->id)}}?pr=>{{$project->id}}" class="btn btn-primary">
                            {{ $stage->name }}
                        </a>    
                        @endforeach
                        <hr>
                        <?php $stages = App\Models\Stage::all(); ?>
                        <ul>
                            @foreach($stages as $stage)
                                @if (!$project->stages->contains($stage))
                                    <li>
                                        <button class="btn btn-danger" onclick="add_stage_to_project({{$stage->id}})"  >{{ $stage->name }}</button>
                                    </li>
                                @endif
                            @endforeach
                        </ul>
                        <hr>
                       
                        <a href="{{ route('stages.create') }}?project={{$project->id}}" class="btn btn-primary"> {{__('Add new stage')}}</a>
                    </div>
               </div>
            </div>
        </div>
   </div>
   <script>
         function add_stage_to_project(stage_id){
             const url = "{{route('projects.add_stage')}}";
             const data = {
                "_token": "{{ csrf_token() }}",
                 "stage_id": stage_id,
                 "project_id": "{{$project->id}}"
             };
             console.log(data);
                fetch(url, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify(data),
                })
                .then(response => response.json())
                .then(data => {
                    console.log('Success:', data);
                    location.reload();
                })
                .catch((error) => {
                    console.error('Error:', error);
                });
            }
   </script>
@endsection