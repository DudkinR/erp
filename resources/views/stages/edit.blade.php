@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
            <h1>{{__('Stages')}}</h1>
                <a class="text-right
                " href="{{ route('stages.index') }}">Back</a>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <form method="POST" action="{{ route('stages.update',$stage) }}">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <input type="hidden" name="_method" value="PUT">
                    <div class="form-group mb-2">
                        <label>{{__('Name')}}</label>
                        <input type="text" class="form-control" name="name" value="{{ $stage->name }}" required>
                    </div>
                    <div class="form-group mb-2">
                        <label>{{__('Description')}}</label>
                        <textarea class="form-control" name="description" rows="5">{{ $stage->description }}</textarea>
                    </div>
                    <div class="form-group mb-2">
                        <?php $steps = App\Models\Step::all(); ?>
                        <label>{{__('Steps')}}</label>
                        <select name="steps_id[]" class="form-control" multiple>
                            @foreach($steps as $step)
                                <option value="{{ $step->id }}" @if(in_array($step->id, $stage->steps->pluck('id')->toArray())) selected @endif>{{ $step->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group mb-2">
                        <label>{{__('New Step')}} <div id="succesful_step"></div> </label>
                        <input type="text" class="form-control" name="new_step" id="new_step" value="">
                        <button type="button" class="btn btn-primary" onclick="add_new_step({{$stage->id}})">{{__('Add')}}</button>
                    </div>
                    <button type="submit" class="btn btn-primary">{{__('Edit')}}</button>
                </form>
            </div>
        </div>
    

    </div>
    <script>
     
@endsection