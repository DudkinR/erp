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
                    <div class="card-body"> 
                    <ul>
                        @foreach($stage->steps as $step)
                            <li id="work_step_id_{{$step->id}}">
                                <a href="{{route("steps.show",$step->id)}}">{{ $step->name }}</a> 
                                <button class="btn btn-danger" onclick="remove_step_from_stage({{$step->id}},{{$stage->id}})"  >X</button>
                         </li>
                        @endforeach
                    </ul>
                    <hr>
                    @if($stage->nextStages->count() > 0)
                    <div class="row">
                        <div class="col-md-12">
                            <h2>{{__('Next Stages')}}</h2>
                        </div>
                        <div class="col-md-12">
                            <ul>
                                @foreach($stage->nextStages as $next_stage)
                                    <li>
                                        <a href="{{ route('stages.show',$next_stage) }}">{{ $next_stage->name }}</a>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                    @endif
                        <?php $steps = App\Models\Step::orderBy('name')->get(); ?> 
                           <h1> {{__('Add step to stage')}}</h1>
                           <select name="steps_id" id="steps_id" class="form-control"
                            onchange="add_step_to_stage({{$stage->id}},
                            this.options[this.selectedIndex].value
                        )">
                            @foreach($steps as $step)
                                @if (!$stage -> steps -> contains($step))
                                    <option value="{{ $step->id }}">{{ $step->name }}</option>
                                @endif
                            @endforeach
                           </select>
                        <hr>
                          <div class="form-group mb-2">
                            <label>{{__('New Step')}} <div id="succesful_step"></div> </label>
                            <input type="text" class="form-control" name="new_step" id="new_step" value="">
                            <button type="button" class="btn btn-primary" onclick="add_new_step({{$stage->id}})">{{__('Add')}}</button>
                        </div>
                    </div>
                    <div class="card-footer">
                        <a href="{{ route('stages.edit',$stage) }}" class="btn btn-primary">{{__('Edit')}}</a>
                    </div>
               </div>
            </div>
        </div>
   </div>
@endsection