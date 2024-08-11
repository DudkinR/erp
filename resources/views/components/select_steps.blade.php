<?php  $step = \App\Models\Step::orderBy('id', 'desc')->get(); ?>
<div class="form-group mb-2">
    <label for="step">{{__('Step')}}</label>
    <select name="step" id="step" class="form-control">
        <option value="0">{{__('New step')}}</option>
        @foreach($step as $step)
            <option value="{{ $step->id }}"
            @if($step_id && $step_id == $step->id)
                selected
            @endif
            >{{ $step->name }}</option>
        @endforeach
    </select>

</div>