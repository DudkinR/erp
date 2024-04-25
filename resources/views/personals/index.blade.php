@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
            <h1>{{__('Personals')}}</h1>
                <a class="text-right" href="{{ route('personal.create') }}">{{__('Create')}}</a>
            </div>
        </div> 
        <div class="row">
            <div class="col-md-12">
                <table class="table">
                    <thead>
                        <tr>
                            <th>{{__('ID')}}</th>
                            <th>{{__('Name')}}</th>
                            <th>{{__('Position')}}</th>
                            <th>
                                {{__('Status')}}
                            </th>
                            <th>{{__('Action')}}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($personals as $personal)
                            <tr>
                                <td>{{ $personal->id }}</td>
                                <td>{{ $personal->fio }}</td>
                                <td>
                                    @foreach($personal->positions as $position)
                                        {{ $position->name }} 
                                    
                                    @endforeach
                                </td>
                                <td
                                 @if($personal->status=='Основна відпустка')
                                  class="bg-warning"
                                @elseif($personal->status=='Хвороба')
                                    class="bg-danger"
                                    @endif 
                                    >
                                   {{ $personal->status}}
                                </td>
                                                                <td>
                                    <a href="{{ route('personal.edit', $personal->id) }}">{{__('Edit')}}</a>
                                    <form action="{{ route('personal.destroy', $personal->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit">{{__('Delete')}}</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>   
    </div>
@endsection