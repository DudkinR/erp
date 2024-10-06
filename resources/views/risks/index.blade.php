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
            <h1>{{__('Experiences')}}</h1>
                <a class="btn btn-light w-100" href="{{ route('risks.create') }}">{{__('Create')}}</a>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <input type="text" id="search" class="form-control" placeholder="{{__('Search')}}"
                onkeyup="filter_experiences();"
                >
            </div>
        </div>    
        <div class="container" id="experiences">
        </div>
    </div>
    <script>
        const experiences = @json($experiences);
        console.log(experiences);
        const search = document.getElementById('search');
        var Vexperiences = experiences;
        function show_experiences() {
            var html = '';
            var i = 0;
            var text = '';
            Vexperiences.forEach(experience => {
                if (experience.text_uk.length == 0 && experience.text_ru.length > 0 ) {
                    text = experience.text_ru.substring(0,250) + '...';
                } else {
                    text = experience.text_uk.substring(0, 250) + '...';
                }
                html += `
                    <div class="row">
                        <div class="col-md-1">
                            ${i++}                            
                        </div>
                        <div class="col-md-1">
                            ${experience.year}
                        </div>
                        <div class="col-md-8">
                            ${text}
                            </div>
                        <div class="col-md-2">
                            <a class="btn btn-warning w-100" href="/risks/${experience.id}/edit">{{__('Edit')}}</a>
                            <a class="btn btn-light w-100" href="/risks/${experience.id}">{{__('Show')}}</a>
                            <form action="/risks/${experience.id}" method="post">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-danger w-100" type="submit">{{__('Delete')}}</button>
                            </form>
                        </div>
                    </div>
                `;
            });
            document.getElementById('experiences').innerHTML = html;
        }
        function filter_experiences() {
            //fillable = ['text_uk','text_ru','text_en',  'npp', 'year', 'consequence', 'accepted', 'author_tn'];
            Vexperiences = 
            experiences.filter(experience => 
                experience.text_uk.toLowerCase().includes(search.value.toLowerCase()) ||
                experience.text_ru.toLowerCase().includes(search.value.toLowerCase()) ||
                experience.text_en.toLowerCase().includes(search.value.toLowerCase()) ||
                experience.npp.toString().includes(search.value) ||
                experience.year.toString().includes(search.value) ||
                experience.consequence.toString().includes(search.value) ||
                experience.accepted.toString().includes(search.value) ||
                experience.author_tn.toString().includes(search.value)
            );
            show_experiences();
        }

    </script>
@endsection