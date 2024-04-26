@extends('layouts.app')
@section('content')
    <div class="container">
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif
        <div class="row">
            <div class="col-md-12">
            <h1>{{__('Projects')}}</h1>
                <a class="text-right" href="{{ route('projects.create') }}">{{__('New project')}}</a>
            </div>
        </div>
        <?php
        // add project Пріоритет	Номер	Дата	Сума	Клієнт	Поточний стан	Строк виконання	% оплати	% відвантаження	% боргу	Валюта	Операція
         // сортируем по дате Строк виконання
        $projects = App\Models\Project::orderBy('date', 'desc')
     //   ->with('cliets')
        ->get();
        ?>

               
        @foreach($projects as $project)
            <div class="row">
                <div class="col-md-12">
                <h2>{{ $project->name }}</h2>
                <p>{{ $project->number }}</p>
                <p>{{ $project->date }}</p>
                <?php 
                $client = App\Models\Client::find($project->client);
                ?>
                <p>{{ $client->name }}</p>
                    <a href="{{ route('projects.show', $project) }}">{{__('Show')}}</a>
                    <a href="{{ route('projects.edit', $project) }}">{{__('Edit')}}</a>
                    <form method="POST" action="{{ route('projects.destroy', $project) }}">
                        @csrf
                        @method('DELETE')
                        <button type="submit">{{__('Delete')}}</button>
                    </form>
                </div>
            </div>
        @endforeach
    
    </div>
@endsection