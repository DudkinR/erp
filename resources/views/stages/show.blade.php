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
                        {{ $stage->name }}
                    </div>
                    <div class="card-body">
                        <p>{{ $stage->description }}</p>
                    </div>
                    <div class="card-body">
                        @foreach($stage->steps as $step)
                            <a href="{{route("steps.show",$step->id)}}">{{ $step->name }}</a>
                        @endforeach
                        <hr>
                        <?php $steps = App\Models\Step::all(); ?>
                        <ul>
                            @foreach($steps as $step)
                                @if (!$stages ->contains($step))
                                    <li>
                                        <button class="btn btn-danger" onclick="add_step_to_stage({{$step->id}})"  >{{ $step->name }}</button>
                                    </li>
                                @endif
                            @endforeach
                        </ul>
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
   <script>
        function add_step_to_stage(step_id){
            const url = "{{route('stages.add_step')}}";
            const data = {
                "_token": "{{ csrf_token() }}",
                "step_id": step_id,
                "stage_id": {{$stage->id}}
            };
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
   </script>
@endsection