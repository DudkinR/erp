@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h1>{{__('Import')}}  </h1>
               <ul>
                    <li><a href="{{ route('structure.import') }}">{{__('Structure import')}}</a>
                   {{ \App\Models\Struct::count();}}
                    </li>
                    <li><a href="{{ route('personal.import') }}"> {{__('Personal import')}}</a>
                    {{ \App\Models\Personal::count();}}
                
                </li>
                    <li><a href="{{ route('types.import') }}"> {{__('Types import')}}</a>
                    {{ \App\Models\Type::count();}}
                </li>
                    <li><a href="{{ route('projects.import') }}"> {{__('Projects import')}}</a>
                    {{ \App\Models\Project::count();}}
                </li>
                    <li><a href="{{ route('dimensions.import') }}"> {{__('Dimensions import')}}</a>
                    {{ \App\Models\Dimension::count();}}
                </li>
                    <li><a href="{{ route('nomenclaturs.import') }}"> {{__('Nomenclatures import')}}</a>
                    {{ \App\Models\Nomenclature::count();}}
                </li>
                    <li><a href="{{ route('docs.import') }}"> {{__('Docs import')}}</a>
                    {{ \App\Models\Doc::count();}}
                </li>
                  
                    <li><a href="{{ route('profiles.import') }}"> {{__('Profiles import')}}</a>
                    {{ \App\Models\User::count();}}
                </li>
                    <li><a href="{{ route('buildings.import') }}"> {{__('Buildings import')}}</a>
                    {{ \App\Models\Building::count();}}
                </li>
                <li><a href="{{ route('risks.import') }}"> {{__('Experiences import')}}</a>
                    {{ \App\Models\Experience::count();}}
                </li>

               </ul>
               
        </div>
        <div class="row">
            <div class="col-md-12">
                <h1>{{__('Clear')}}  </h1>
                <ul>
                      <li><a href="{{ route('tasks.clear') }}">{{__('Tasks clear')}}</a>
                     {{ \App\Models\Task::count();}}
                     </li>
                </ul>
            </div>
        </div>         

    </div>
@endsection