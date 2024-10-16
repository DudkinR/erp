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
                <a class="btn btn-warning w-100" href="{{ route('callings.create') }}">{{__('New')}}</a>
            </div>
        </div>    
        <div class="container">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>{{__('Department')}}</th>
                        <th>{{__('Name')}}</th>
                        <th>{{__('Start')}}</th>
                        <th>{{__('In work')}}</th>
                        <th>{{__('Completed')}}</th>
                        <th>{{__('Number of people')}}</th>
                        <th>{{__('Action')}}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($callings as $calling)
                    <tr>
                        <td>rr</td>
                        <td>{{$calling->description}}</td>
                        <td>{{$calling->start}}</td>
                        <td>{{$calling->in_work}}</td>
                        <td>{{$calling->completed}}</td>
                        <td>{{$calling->number_of_people}}</td>
                        <td>
                            <a class="btn btn-warning" href="{{ route('callings.edit',$calling) }}">{{__('Edit')}}</a>
                            <a class="btn btn-success" href="{{ route('callings.show',$calling) }}">{{__('Show')}}</a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        
        </div>
    </div>
    <script>
        const search = document.getElementById('search');
        var Vcallings = callings;
        function show_collings() {
            var html = '';
            Vcallings.forEach(calling => {
                html += `
                    <div class="row">
                        <div class="col-md-12">
                            <a class="btn btn-light w-100" href="/confirmSS/${calling.id}">${calling.description}</a>
                        </div>
                    </div>
                `;
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