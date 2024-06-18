@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h1>{{__('New problem')}}</h1>
                <form method="POST" action="{{ route('problems.store') }}">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <?php  
                        $projectsList = \App\Models\Project::where('current_state', '<>', 'Закритий')->get(); 
                        $type_file="create";
                   ?>
                    @include('components.select_projects')
                    @include('components.select_stages')
                    @include('components.select_steps')
                    @include('components.description_steps')
                    @include('components.input_file_image')
                    <div class="row">
                        <div class="col-md-6">
                            @include('components.select_controls')
                        </div>
                        <div class="col-md-6">
                            <h1>{{__('New control')}}</h1>
                            @include('components.input_name')
                            @include('components.input_description')
                        </div>
                    </div>
                    @include('components.number_priority')
                    @include('components.date_start')
                    @include('components.date_end')
                    @include('components.deadline')
                    @include('components.select_status')
                    @include('components.select_positions')
                    @include('components.button_create')
                </form>
            </div>
        </div>
    </div>
@endsection