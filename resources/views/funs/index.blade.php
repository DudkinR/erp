@extends('layouts.app')
@section('content')
    <div class="container">
        @if(isset($goal))
            <div class="row">
                <div class="col-md-12">
                    <h1>{{ $goal->name }}</h1>
                    <p>{{ $goal->description }}</p>
                    <p>{{ __('Due Date') }}: {{ $goal->due_date }}</p>
                    <p>{{ __('Status') }}: 
                        @if($goal->status == '0')
                            {{ __('Not Started') }}
                        @elseif($goal->status == '1')
                            {{ __('In Progress') }}
                        @elseif($goal->status == '2')
                            {{ __('Complete') }}
                        @endif  
                    </p>
                    @if($goal->status == '2')
                        <p>{{ __('Completed On') }}: {{ $goal->completed_on }}</p>
                    @endif
                    <a href="{{ route('goals.edit', $goal->id) }}" class="btn btn-warning">{{ __('Edit') }}</a>
                    <form style="display:inline-block" method="POST" action="{{ route('goals.destroy', $goal->id) }}">
                        @method('DELETE')
                        @csrf
                        <button type="submit" class="form-control btn btn-danger">{{ __('Delete') }}</button>
                    </form>
                </div>
            </div>
        @endif
        <div class="row">
            <div class="col-md-12">
                <h1>{{ __('Functions') }}</h1>
                <a class="text-right" href="{{ route('funs.create') }}">{{ __('Create Function') }}</a>
                <table class="table">
                    <thead>
                        <tr>
                            <th>{{ __('Title') }}</th>
                            <th>{{ __('Description') }}</th>
                            <th>{{ __('Actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($funs as $funct)
                            <tr>
                                <td
                                class = "@if($funct->positions->count() == 0)
                                    bg-danger
                                    @endif
                                    "
                                >
                                    {{ $funct->name }}
                                
                                </td>
                                <td>
                                    <p>
                                    {{ $funct->description }}
                                </p>
                                <p>
                                   <h6> {{__('Goals')}}:</h6>
                                    <ul>
                                    @foreach($funct->goals as $goal)
                                        <li>{{ $goal->name }} </li>
                                    @endforeach
                                    </ul>

                                <p>
                                <h6> {{ __('Objective') }}: </h6>
                                    <ul>
                                    @foreach($funct->objectives as $objective)
                                        <li>{{ $objective->name }} </li>
                                    @endforeach
                                    </ul>
                                </p>

                                </td>
                                <td>
                                    <a href="{{ route('funs.show', $funct->id) }}" class="btn btn-default">{{ __('View') }}</a>
                                    <a href="{{ route('funs.edit', $funct->id) }}" class="btn btn-warning">{{ __('Edit') }}</a>
                                    <form style="display:inline-block" method="POST" action="{{ route('funs.destroy', $funct->id) }}">
                                        @method('DELETE')
                                        @csrf
                                        <button type="submit" class="form-control btn btn-danger">{{ __('Delete') }}</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
