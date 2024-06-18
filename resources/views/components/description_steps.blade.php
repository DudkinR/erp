<div class="form-group mb-2">
    <label for="description" class="form-label">{{__('Description')}}</label>
    <textarea class="form-control" id="description" name="description" rows="3" required>@if(isset($problem)){{$problem->description}}@endif</textarea>    
</div>