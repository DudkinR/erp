@extends('layouts.app')
@section('content')
    <div class="container">
               @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        @if(session('success'))
        <div class="alert alert-success">{{ __(session('success')) }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ __(session('error')) }}</div>
    @endif
        <div class="row">
            <div class="col-md-12">
                <h1>{{__('Goal')}}</h1>
                <div class="card">
                    <div class="card-header">
                        {{ $goal->name }}
                    </div>
                    <div class="card-body">
                        <p>{{ $goal->description }}</p>
                        <p><strong>{{__('Due Date')}}:</strong> {{ $goal->due_date }}</p>
                        <p><strong>{{__('Priority')}}:</strong> {{ $goal->priority }}</p>
                        <h3>
                            {{__('Objectives')}}
                        </h3>
                        @foreach($goal->objectives as $objective)
                            <div class="card">
                                <div class="card-header">
                                    {{ $objective->name }}
                                </div>
                                <div class="card-body">
                                      <h6>{{__('Functions')}}</h6>
                                            <ul>
                                                @foreach($objective->functs as $fun)
                                                    <li>{{ $fun->name }}</li>
                                                @endforeach
                                            </ul>
                                            @if(Auth::user()->hasRole('admin'))
                                           
                                            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addFunctionModal">
                                                {{__('Add Function')}}
                                            </button> @endif
                                </div>
                            </div>
                        @endforeach
                    </div>     @if(Auth::user()->hasRole('admin'))
                   
                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addObjectiveModal">
                        {{__('Add Objective')}}
                    </button> @endif
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="addObjectiveModal" tabindex="-1" role="dialog" aria-labelledby="addObjectiveModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                @if(Auth::user()->hasRole('admin'))
                
                <form method="POST" action="{{ route('objectives.store') }}">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addObjectiveModalLabel">{{__('Add Objective')}}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body
                    ">
                        @csrf
                        <input type="hidden" name="goal_id" value="{{ $goal->id }}">
                        <div class="form-group
                        ">
                            <label for="name">{{__('Name')}}</label>
                            <input type="text" class="form-control" id="name" name="name" required>
                        </div>
                        <div class="form-group
                        ">
                            <label for="description">{{__('Description')}}</label>
                            <textarea class="form-control" id="description" name="description" required></textarea>
                        </div>
                    </div>
                    <div class="modal-footer
                    ">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">{{__('Close')}}</button>
                        <button type="submit" class="btn btn-primary">{{__('Save')}}</button>
                    </div>
                </form>
                @endif
            </div>
        </div>
    </div>
    <div class="modal fade" id="addFunctionModal" tabindex="-1" role="dialog" aria-labelledby="addFunctionModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">    
                 @if(Auth::user()->hasRole('admin'))
                
                <form method="POST" action="{{ route('funs.store') }}">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addFunctionModalLabel">{{__('Add Function')}}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body
                    ">
                        @csrf
                        <input type="hidden" name="objective_id" value="{{ $objective->id }}">
                        <div class="form-group
                        ">
                            <label for="name">{{__('Name')}}</label>
                            <input type="text" class="form-control" id="name" name="name" required>
                        </div>
                        <div class="form-group
                        ">
                            <label for="description">{{__('Description')}}</label>
                            <textarea class="form-control" id="description" name="description" required></textarea>
                        </div>
                    </div>
                    <div class="modal-footer ">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">{{__('Close')}}</button>
                        <button type="submit" class="btn btn-primary">{{__('Save')}}</button>
                    </div>
                </form>
                @endif
            </div>
        </div>
    </div>
</div>
<script>
   
</script>

@endsection
