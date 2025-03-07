@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
            <h1>{{__('Show')}}</h1>
                <a class="text-right" href="{{ route('projects.index') }}">
                    {{__('Back')}}

                </a>
            </div>  
        </div>
        <?php $client = App\Models\Client::find($project->client); ?>
    @if($client)
        <div class="row">
            <div class="col-md-12">
                <h1>
                    {{ $client->name }}
                </h1>
            </div>
        </div>
    @endif
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
                        @if(Auth::user()->hasRole('moderator','admin','quality-engineer'))
                        <p>
                            <a href="{{route('projects.add_stage_form')}}?project_id={{$project->id}}" class="btn btn-primary"> {{__('Stages')}}</a>
                        </p>
                        @endif
                        <p>
                            <a href="{{route('projects.projectstgantt',$project->id)}}" class="btn btn-primary"> {{__('Gantt')}}</a>
                        </p>
                    </div>
                    <div class="card-footer">
                        @foreach($project->stages as $stage)
                        <a href="{{route("stages.show",$stage->id)}}?project_id={{$project->id}}" class="btn btn-primary">
                            {{ $stage->name }}
                        </a>    
                        @endforeach
                        <hr>
                        <div class="row">
                            <div class="col-md-6">
                               <ul>
                                <?php $stages = App\Models\Stage::orderBy('id', 'desc')->get(); ?> 
                                    @foreach($stages as $stage)
                                        @if (!$project->stages->contains($stage))
                                            <li>
                                                <button class="btn btn-danger" onclick="add_stage_to_project(
                                                    {{$stage->id}},
                                                    {{$project->id}} , 
                                                    document.getElementById('deadline').value,
                                                    document.getElementById('responsible_position_id').value
                                                                                                      
                                            )"  >{{ $stage->name }}</button>
                                            </li>
                                        @endif
                                    @endforeach
                                </ul> 
                            </div>
                            <div class="col-md-6"> @if(Auth::user()->hasRole('moderator','admin','quality-engineer'))
                                <label for ="deadline"> {{__('Deadline')}}</label>
                                <input type="date" id="deadline" class="form-control">
                                <label for ="responsible_position_id"> {{__('Responsible Position')}}</label>
                                <select id="responsible_position_id" class="form-control">
                                    <option value="">{{__('Select')}}</option>
                                    <?php $positions = App\Models\Position::orderBy('id', 'desc')->get(); ?>
                                    @foreach($positions as $position)
                                        <option value="{{$position->id}}">{{$position->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <?php $stages = App\Models\Stage::orderBy('id', 'desc')->get(); ?>
                        
                        <hr>
                       
                        <a href="{{ route('stages.create') }}?project={{$project->id}}" class="btn btn-primary"> {{__('Add new stage')}}</a>
                        @endif
                    </div>
               </div>
            </div>
        </div>
   </div>
   <script>
        
   </script>
@endsection