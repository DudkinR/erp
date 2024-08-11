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
                <form action="{{route('projects.add_stage')}}" method="post">
                    @csrf
                    <?php $projects = App\Models\Project::orderBy('id', 'desc')->get(); ?>
                    <label for="project_id">{{__('Select Project')}}</label>
                    <select name="project_id" class="form-control">
                        <option value="">{{__('Select')}}</option>
                        @foreach($projects as $project)
                            <option value="{{$project->id}}" 
                                @if($project->id == request()->get('project_id'))
                                    selected
                                @endif
                                >{{$project->name}}</option>
                        @endforeach
                    </select>
                    <label for="stage_id">{{__('Select Stage')}}</label>
                    <select name="stage_id" class="form-control">
                        <option value="">{{__('Select')}}</option>
                        <?php $stages = App\Models\Stage::orderBy('id', 'desc')->get(); ?>
                        @foreach($stages as $stage)
                            <option value="{{$stage->id}}">{{$stage->name}}</option>
                        @endforeach
                    </select>
                    <label for="deadline">{{__('Deadline')}}</label>
                    <input type="date" name="deadline" class="form-control">
                    <label for="responsible_position_id">{{__('Responsible Position')}}</label>
                    <select name="responsible_position_id" class="form-control">
                        <option value="">{{__('Select')}}</option>
                        <?php $positions = App\Models\Position::orderBy('id', 'desc')->get(); ?>
                        @foreach($positions as $position)
                            <option value="{{$position->id}}">{{$position->name}}</option>
                        @endforeach
                    </select>
                    <button type="submit" class="btn btn-primary">{{__('Add Stage')}}</button>
                </form>
             </div>   
        </div>
   </div>
@endsection