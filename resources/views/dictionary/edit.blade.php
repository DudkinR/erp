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
            <h1>{{__('Edit')}}</h1>
                
            </div>
        </div>
        @foreach($words as $word)
        <div class="row" @if($word->editor == null || $word->editor == '')  style="background-color: #f4f440;" @endif>    
            <div class="col-md-8">
                <form method="POST" action="{{ route('dictionary.update', $word->id) }}">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <input type="hidden" name="_method" value="PUT">
                     <label for="en">{{__('en')}}</label>
                        <input type="text" class="form-control" id="en" name="en" value="{{ $word->en }}">
                    <label for="uk">{{__('uk')}}</label>
                        <input type="text" class="form-control" id="uk" name="uk" value="{{ $word->uk }}">
                    <label for="ru">{{__('ru')}}</label>
                        <input type="text" class="form-control" id="ru" name="ru" value="{{ $word->ru }}">
                    <label for="description">{{__('description')}}</label>
                       <textarea class="form-control" id="description" name="description">{{ $word->description }}</textarea>
                    <label for="example">{{__('example')}}</label>
                       <textarea class="form-control" id="example" name="example">{{ $word->example }}</textarea>
                    <button type="submit" class="btn btn-primary w-100">{{__('Update')}}</button>
                </form>
            </div>
            <div class="col-md-4">
                <form method="POST" action="{{ route('dictionary.destroy', $word->id) }}">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <input type="hidden" name="_method" value="DELETE">
                    <button type="submit" class="btn btn-danger w-100">{{__('Delete')}}</button>
                </form>
            </div>
        </div>
        @endforeach
    </div>
@endsection