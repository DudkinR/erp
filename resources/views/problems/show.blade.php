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
            <div class="alert alert-success">
                {{ __(session('success')) }}
            </div>
        @endif
        <div class="row">
            <div class="col-md-12">
            <h1>{{__('Problem')}}</h1>
                <a  href="{{ route('problems.index') }}" class="btn btn-primary">
                    {{__('Back')}}
            </a>
            </div>  
        </div>
        <div class="row">
            <div class="col-md-12">
                <h2>{{ $problem->name }}</h2>
                <p>{{ $problem->description }}</p>
                <p>{{__('Priority')}} : {{ $problem->priority }}</p>
                <p>
                    {{__('Date start')}}:
                    {{ $problem->date_start }}</p>
                <p>
                {{__('Date end')}}    :
                {{ $problem->date_end }}</p>
                <p>
                {{__('Deadline')}}  :  
                {{ $problem->deadline }}</p>
                <hr>
                @foreach($problem->personals as $personal)
                    <p>{{ $personal->fio }} :
                        @if($personal->pivot->view == 0)
                            {{__('Not viewed')}}
                        @else
                            {{__('Viewed')}}
                        @endif
                        <br>
                        {{__('Comment')}} : {{ $personal->pivot->comment }}
                        
                </p>

                @endforeach
                @if(Auth::user()->hasRole('moderator','admin','quality-engineer'))

                <hr>
                <a href="{{ route('problems.edit', $problem->id) }}">{{__('Edit')}}</a>
                <hr>
                <form method="POST" action="{{ route('problems.update', $problem->id) }}">
                    @csrf
                    @method('PUT')
                    <div class="form-group mb-2">
                        <label for="personal">{{__('Personal')}}</label>
                        <select name="personal[]" id="personal" class="form-control" multiple>
                            <option value="0">{{__('Не призначен')}}</option>
                            @foreach($personals as $personal)
                                <option value="{{ $personal->id }}"
                                @if($problem->personals()->where('personal_id', $personal->id)->exists())
                                    selected
                                @endif
                                >{{ $personal->fio }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group mb-2">
                        <label for="view">{{__('View')}}</label>
                        <select name="view" id="view" class="form-control">
                            <option value="0" {{ $problem->view == 0 ? 'selected' : '' }}>{{__('Не переглянуто')}}</option>
                            <option value="1" >{{__('Переглянуто')}}</option>
                        </select>
                    </div>
                    <div class="form-group mb-2 bg-warning">
                        <label for="comment">{{__('Comment')}}</label>
                        <textarea name="comment" id="comment" class="form-control" rows=5>{{ $problem->comment }}</textarea>
                    </div>
                    <p>{{ $problem->status }}</p>
                    <div class="form-group mb-2">
                        <label for="status">{{__('Status')}}</label>
                        <select name="status" id="status" class="form-control">
                            <option value="new"
                            @if($problem->status == 'new')
                                selected
                            @endif
                            
                            >{{__('New')}}</option>
                            <option value="in_progress"
                            @if($problem->status == 'in_progress')
                                selected
                            @endif
                            
                            >{{__('In progress')}}</option>
                            <option value="done"
                            @if($problem->status == 'done')
                                selected
                            @endif
                            
                            >{{__('Done')}}</option>
                            <option value="closed"
                            @if($problem->status == 'closed')
                                selected
                            @endif
                            
                            >{{__('Closed')}}</option>
                        </select>
                    </div>

                    <button type="submit">{{__('Save')}}</button>
                </form>
                <hr>
                @endif
            </div>
        </div>
   </div>
@endsection