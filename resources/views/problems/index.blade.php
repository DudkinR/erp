@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
            <h1>{{__('Problems')}} </h1>
              
            </div>
        </div>  
       
         @if(Auth::user()->hasRole('moderator','admin','quality-engineer'))
          @if(isset($_GET['project_id']))
        @php $project = App\Models\Project::find($_GET['project_id']); @endphp
        <div class="row">
            <div class="col-md-12">
                <h2>{{ $project->name }}</h2>
                <a class="text-right btn btn-info" href="{{ route('problems.create') }}?project_id={{$project->id}}"  >{{__('Create')}}</a>
            </div>
        </div>
        @else
       
        <div class="row">
            <div class="col-md-12">
                <a class="text-right btn btn-info" href="{{ route('problems.create') }}"  >{{__('Create')}}</a>
            </div>
        </div>
        @endif

        @endif
      @foreach($problems as $problem)
        <div class="row">
            <div class="col-md-12">
                <h2><a href="{{ route('problems.show', $problem) }}">{{ $problem->name }}</a></h2>
                <p>{{ $problem->description }}</p>
                @if(Auth::user()->hasRole('moderator','admin','quality-engineer'))
                <a href="{{ route('problems.edit', $problem) }}">{{__('Edit')}}</a>
                <form action="{{ route('problems.destroy', $problem) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit">{{__('Delete')}}</button>
                </form>
                @endif
            </div>
        </div> 
        @endforeach
    </div>
@endsection