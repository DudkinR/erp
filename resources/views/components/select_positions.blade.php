<?php
$positions = \App\Models\Position::all();
?>
<div class="form-group mb-2">
<label for="positions">{{__('Positions')}}</label>
<select name="positions" id="positions" class="form-control">
    
    @foreach($positions as $position)
        <option value="{{ $position->id }}">{{ $position->name }}</option>
    @endforeach
</select>
</div>