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
            <input type="text" id="search" class="form-control" placeholder="{{__('Search')}}">
        </div>
    </div>
        <div class="row">
            <div class="col-md-12">
            <h1>{{__('Form of callings')}}</h1>
                <a class="btn btn-light w-100" href="{{ route('callings.create') }}">{{__('Create')}}</a>
            </div>
        </div>    
        <div class="container" id="callings">


        </div>
    </div>
    <script>
        const search = document.getElementById('search');
        const callings = @json($callings);
        var Vcallings = callings;
        function show_collings()
        {
            var html = '';
            Vcallings.forEach(calling => {
                html += '<div class="row">';
                html += '<div class="col-md-12">';
                html += '<a class="btn btn-light w-100" href="{{ route('callings.index') }}/'+calling.id+'/edit">'+calling.description+'</a>';
                html += '</div>';
                html += '</div>';
            });
            document.getElementById('callings').innerHTML = html;
        }
        show_collings();
        search.addEventListener('keyup', (e) => {
            Vcallings = callings.filter(calling => calling.description.toLowerCase().includes(e.target.value.toLowerCase()));
            show_collings();
        });
    </script>
@endsection