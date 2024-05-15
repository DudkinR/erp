
<?php  $stage = \App\Models\Stage::all();                  
?>
<div class="form-group mb-2">
    <label for="stage">{{__('Stage')}}</label>
    <select name="stage" id="stage" class="form-control">
        <option value="0">{{__('New stage')}}</option>
        @foreach($stage as $stage)
            <option value="{{ $stage->id }}"
            @if($stage_id && $stage_id == $stage->id)
                selected
            @endif
            >{{ $stage->name }}</option>
        @endforeach
    </select>
</div>