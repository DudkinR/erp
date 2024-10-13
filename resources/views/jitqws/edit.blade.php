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
            <h1>{{__('Editor question')}}</h1>
                <form method="POST" action="{{ route('jitqws.update',$jitqw) }}">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <input type="hidden" name="_method" value="PUT">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="description_uk">{{__('Description UK')}}</label>
                                <textarea class="form-control" id="description_uk" name="description_uk" rows="3" required>{{$jitqw->description_uk}}</textarea>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="description_en">{{__('Description EN')}}</label>
                                <textarea class="form-control" id="description_en" name="description_en" rows="3">{{$jitqw->description_en}}</textarea>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="description_ru">{{__('Description RU')}}</label>
                                <textarea class="form-control" id="description_ru" name="description_ru" rows="3">{{$jitqw->description_ru}}</textarea>
                            </div>
                        </div>
                    </div>
                    @forEach($briefs as $brief)
                    <div class="form-check">
                        <input class="form-check" type="checkbox" name="briefs[]" value="{{$brief->id}}" @if($jitqw->briefs->contains($brief->id)) checked @endif>
                        @if($brief->name_uk!='')<label class="form-check" for="briefs">{{$brief->name_uk}}</label>
                        @elseif($brief->name_en!='')<label class="form-check" for="briefs">{{$brief->name_en}}</label>
                        <a href="{{ route('briefs.edit', $brief->id) }}">{{__('Edit')}}</a>
                        @else<label class="form-check" for="briefs">{{ str_replace('&nbsp;', ' ', strip_tags($brief->name_ru))}}</label>
                        <a href="{{ route('briefs.edit', $brief->id) }}">{{__('Edit')}}</a>
                        @endif  
                    </div>
                    @endforeach

                   
                    <button type="submit" class="btn btn-primary w-100">{{__('Update')}}</button>
                </form>
            </div>
        </div>
    </div>
@endsection