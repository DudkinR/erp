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
            <h1>{{__('Brief')}}</h1>
                <form method="POST" action="{{ route('briefs.update',$brief) }}">
                    @csrf
                    @method('PUT') <!-- Метод для обновления данных -->
                    <input type="hidden" name="id" value="{{ $brief->id }}">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="name_uk">{{__('Name UK')}}</label>
                                <textarea class="form-control" id="name_uk" name="name_uk" rows="3" ondblclick="select_text()"
                                @if($brief->name_uk=='') style="backgroundColor:red" @endif
                                 required>{{ $brief->name_uk }}</textarea>
                        </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="name_en">{{__('Name EN')}}</label>
                                <textarea class="form-control" id="name_en" name="name_en" rows="3" ondblclick="select_text()" >{{ $brief->name_en }}</textarea>
                                </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="name_ru">{{__('Name RU')}}</label>
                                <textarea class="form-control" id="name_ru" name="name_ru" rows="3" ondblclick="select_text()" >{{ str_replace('&nbsp;', ' ', strip_tags($brief->name_ru)) }}</textarea>
                            </div>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary w-100">{{__('Update')}}</button>
                </form>
            </div>
        </div>
    </div>
@endsection