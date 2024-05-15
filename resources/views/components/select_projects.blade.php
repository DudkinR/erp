<div class="form-group mb-2">
    <label for="project">{{__('Project')}}  {{ $project_id }}</label>
    <select name="project" id="project" class="form-control">
        <option value="0">{{__('New project')}}</option>
        <?php 
        if(!isset($projectsList))
        {
            $projectsList = \App\Models\Project::all(); 
        }
        if(!isset($project_id))
        {
            $project_id = 0;
        }
        ?>
        @foreach($projectsList as $projectItem)
            <option value="{{ $projectItem->id }}" {{ $project_id == $projectItem->id ? 'selected' : '' }}>
                {{ $projectItem->name }} 
            </option>
        @endforeach
    </select>
</div>