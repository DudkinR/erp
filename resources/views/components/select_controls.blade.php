<?php  $control = \App\Models\Control::orderBy('id', 'desc')->get(); ?>
<div class="form-group mb-2">
    <label for="control">{{__('Control')}}</label>
    <select name="control" id="control" class="form-control">
        <option value="0">{{__('New control')}}</option>
        @foreach($control as $control)
            <option value="{{ $control->id }}"
            @if($control_id && $control_id == $control->id)
                selected
            @endif
            >{{ $control->name }}</option>
        @endforeach
    </select>
</div>
