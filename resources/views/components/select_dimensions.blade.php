<!-- $dimensions in resources/views/components/dimensions.blade.php   include -->
@include('components.dimensions')
<div class="form-group mb-2">
    <label for="dimensions">{{__('Dimensions')}}</label>
    <select name="dimension" id="dimension" class="form-control">
        <option value=""></option>
        @foreach($dimensions as $dimension)
            <option value="{{ $dimension['value'] }}"
            @if($dimension_id && $dimension_id == $dimension['value'])
                selected
            @endif
            >{{ $dimension['label'] }}</option>

    </select>
</div>
