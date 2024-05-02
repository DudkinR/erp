@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
            <h1>{{__('Stage')}}</h1>
                <a class="text-right
                " href="{{ route('stages.index') }}">Back</a>
            </div>  
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <H1>{{ $stage->name }}</H1>
                    </div>
                    <div class="card-body">
                        <p>{!! nl2br(e($stage->description)) !!}</p>
                    </div>
                    <div class="card-body"> <ul>
                        @foreach($stage->steps as $step)
                            <li id="work_step_id_{{$step->id}}">
                                <a href="{{route("steps.show",$step->id)}}">{{ $step->name }}</a> 
                                <button class="btn btn-danger" onclick="remove_step_from_stage({{$step->id}},{{$stage->id}})"  >{{__('Remove')}}{{$step->id}},{{$stage->id}}</button>
                         </li>
                        @endforeach
                    </ul>
                        <hr>
                        <?php $steps = App\Models\Step::all(); ?>
                           <h1> {{__('Add step to stage')}}</h1>
                           <select name="steps_id" id="steps_id" class="form-control">
                            @foreach($steps as $step)
                                @if (!$stage -> steps -> contains($step))
                                    
                                        <button class="btn btn-danger" onclick="add_step_to_stage({{$step->id}})"  >{{ $step->name }}</button>
                                    
                                @endif
                            @endforeach
                       
                        <hr>
                       
                        <a href="{{ route('steps.create') }}?stage={{$stage->id}}" class="btn btn-primary"> {{__('Add new step')}}</a>
      
                    </div>
                    <div class="card-footer">
                        <a href="{{ route('stages.edit',$stage) }}" class="btn btn-primary">{{__('Edit')}}</a>
                        <form method="POST" action="{{ route('stages.destroy',$stage) }}">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <input type="hidden" name="_method" value="DELETE">
                            <button type="submit" class="btn btn-danger">{{__('Delete')}}</button>
                        </form>
                    </div>
               </div>
            </div>
        </div>
   </div>
@endsection