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
                <a class="btn btn-light w-100" href="{{ route('experiences') }}">{{__('Experiences')}}</a>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
            <h1>{{__('Add Experiences')}}</h1>
                <a class="btn btn-light w-100" href="{{ route('risks.create') }}">{{__('Create')}}</a>
            </div>
        </div>
        @foreach($experiences as $experience)
        <div class="row">
            <div class="col-md-3"@if($experience->text_uk=='') style="background-color: #ff0000;" @endif>                
                {{$experience->text_uk}}
            </div>
            <div class="col-md-3">
                {{$experience->text_en}}
            </div>
            <div class="col-md-3">
                {{$experience->text_ru}}
            </div>
            <div class="col-md-3">
                <a class="btn btn-warning w-100" href="{{ route('risks.edit', $experience->id) }}">{{__('Edit')}}</a>
                <form method="POST" action="{{ route('risks.destroy', $experience->id) }}">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger w-100">{{__('Delete')}}</button>
                </form>
            </div>
        </div>
        <hr style="border: 1px solid black;">
        @endforeach
    </div>
    <script>
       

    </script>
@endsection