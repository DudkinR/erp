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
                <h1>{{__('Experience import')}} {{$experiences->count()}}</h1>
                <p>
                    {{$experiences->first()->text_ru}}
                </p>

                <form method="POST" action="{{ route('risks.importData') }}"  enctype="multipart/form-data">
                    @csrf
                    <div class="container bg-info">
                        <div class="row">
                            <div class="col-md-12">
                                <label for="causes">{{__('Causes')}}</label>
                                <select class="form-control" size=5 name="causes[]" id="causes" multiple>
                                    @foreach($causes as $cause)
                                        <option value="{{$cause->id}}">{{$cause->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <label for="Search_word">{{__('Search word')}}</label>
                                <input type="text" class="form-control" name="Search_word" id="Search_word" value="{{ old('Search_word') }}">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <button type="submit" class="btn btn-primary">{{__('Import')}}</button>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <label for="equipments">{{__('Use Equipments')}}</label>
                                <select class="form-control" size=5 name="equipments[]" id="equipments" multiple>
                                    @foreach($equipments as $equipment)
                                        <option value="{{$equipment->id}}">{{$equipment->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <label for="systems">{{__('Use Systems')}}</label>
                                <select class="form-control" size=5 name="systems[]" id="systems" multiple>
                                    @foreach($systems as $system)
                                        <option value="{{$system->id}}">{{$system->uk}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <label for="action">{{__('Main Action')}}</label>
                                <select class="form-control" size=5 name="action" id="action" >
                                    @foreach($actions as $action)
                                        <option value="{{$action->name}}">{{$action->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <label for="addition_actions">{{__('Addition Actions (JIT)')}}</label>
                                <select class="form-control" size=5 name="addition_actions[]" id="addition_actions" multiple>
                                    @foreach($addition_actions as $action)
                                        <option value="{{$action->id}}">{{$action->name_ru}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                  
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection