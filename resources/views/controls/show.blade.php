@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
            <h1>{{__('control show')}}</h1>
                <a class="text-right
                " href="{{ route('controls.index') }}">Back</a>
            </div>  
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        {{ $control->name }}
                    </div>
                    <div class="card-body">
                        <p>{{ $control->description }}</p>
                    </div>
                    <div class="card-body">
                        @foreach($control->steps as $step)
                            <a href="{{route("steps.show",$step->id)}}">{{ $step->name }}</a>
                        @endforeach
                        <hr>
                        <?php $steps = App\Models\Step::all(); ?>
                        <ul>
                            @foreach($steps as $step)
                                @if (!$control -> steps -> contains($step))
                                    <li>
                                        <button class="btn btn-danger" onclick="add_step_to_control({{$step->id}})"  >{{ $step->name }}</button>
                                    </li>
                                @endif
                            @endforeach
                        </ul>
                        <hr>
                        <a href="{{ route('steps.create') }}?control={{$control->id}}" class="btn btn-primary"> {{__('Add new step')}}</a>
                    </div>
                    <div class="card-footer">
                        <a href="{{ route('controls.edit',$control) }}" class="btn btn-primary">{{__('Edit')}}</a>
                        <form method="POST" action="{{ route('controls.destroy',$control) }}">
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