@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
            <h1>{{__('Step')}}</h1>
                <a class="text-right
                " href="{{ route('steps.index') }}">Back</a>
            </div>  
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        {{ $step->name }}
                    </div>
                    <div class="card-body">
                        <p>{{ $step->description }}</p>
                    </div>  

               </div>
            </div>
        </div>
        <div class="row">
            <h1>{{__('Controls')}}</h1>
            <?php $controls = $step->controls; ?>
            @foreach($controls as $control)
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <a href="{{ route('controls.show', $control->id) }}">{{ $control->name }}</a>
                        </div>
                        <div class="card-body">
                            <p>{{ $control->description }}</p>
                        </div>
                    </div>
                </div>
            @endforeach
            <?php $controls_all = \App\Models\Control::all(); ?>
            <div class="col-md-12">
                @foreach($controls_all as $control)
                    @if(!$controls->contains($control))
                        <button class="btn btn-danger" onclick="add_control({{$control->id}})">{{$control->name}} </button>
                    @endif
                @endforeach
        </div>
   </div>
</div>
<script>
    function add_control(control_id) {
        const url = `{{ route('steps.add_control') }}`;
        const data = {
            _token: "{{ csrf_token() }}",
            step_id: {{$step->id}},
            control_id: control_id
        };
        fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify(data)
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            }
        });

                 
    }
</script>
@endsection