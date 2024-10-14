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
                   
                    <div class="row mb-3 p-3" style="background-color: #fff0f5; border-radius: 8px;">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="order">{{__('Count')}}</label>
                                <input type="number" class="form-control" id="order" name="order" value="{{ $brief->order }}">
                            </div>
                        </div>
                            <div class="col-md-3">
                            <div class="form-group" >
                                <label for="type">{{__('Type')}}</label>
                                <select class="form-control" id="type" name="type">
                                    <option value="1" @if($brief->type==1) selected @endif>{{__('Not Nesessary')}}</option>
                                    <option value="2" @if($brief->type==2) selected @endif>{{__('Nesessary')}}</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group" >
                                <label for="risk">{{__('Risk influence')}}</label>
                                <select class="form-control" id="risk" name="risk">
                                    <option value="1" @if($brief->risk==1)  selected @endif>1</option>
                                    <option value="2" @if($brief->risk==2)  selected @endif>2</option>
                                    <option value="3" @if($brief->risk==3)  selected @endif>3</option>
                                    <option value="4" @if($brief->risk==4)  selected @endif>4</option>
                                    <option value="5" @if($brief->risk==5)  selected @endif>5</option>
                                
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group" >
                                <label for="functional">{{__('Time of action')}}</label>
                                <select class="form-control" id="functional" name="functional">
                                    <option value="3" @if($brief->functional==3) selected @endif>{{__('After the briefing')}}</option>
                                    <option value="2" @if($brief->functional==2) selected @endif>{{__('Into the briefing\'s time')}}</option>
                                    <option value="1" @if($brief->functional==1) selected @endif>{{__('Before the briefing')}}</option>
                                </select>
                            </div>
                        </div>
                    </div>


                    <div class="row mb-3 p-3" style="background-color: #fff0f5; border-radius: 8px;">
                        <div class="col-md-6 border">
                            <div class="form-group">
                                <label>{{ __('Actions') }} {{__('where we can used')}}</label>
                                @foreach($actions as $action)
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="actions[]" value="{{ $action->id }}">
                                        <label class="form-check-label">{{ $action->name }}</label>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        <div class="col-md-6 border">
                            <div class="form-group">
                                <label>{{ __('Causes') }} {{__('what we want to delete')}}</label>
                                @foreach($causes as $cause)
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="causes[]" value="{{ $cause->id }}">
                                        <label class="form-check-label">{{ $cause->name }}</label>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    <div class="row mb-3 p-3" style="background-color: #f597b6; border-radius: 8px;">
                        <h1>{{ __('Only for information') }}</h1>
                        <div class="col-md-12 border">
                            <div class="form-group">
                                <label>{{ __('JITs') }}</label>
                                <select name="jits[]" id="jits" multiple class="form-control">
                                    @foreach($jits as $jit)
                                        <option value="{{ $jit->id }}"
                                            @if($myjits && in_array($jit->id, $myjits)) selected @endif>
                                            @if($jit->name_uk!=='')
                                                {{ $jit->name_uk }}
                                            @elseif($jit->name_en!=='')
                                                {{ $jit->name_en }}
                                            @else
                                            {{ $jit->name_ru }}
                                            @endif
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">{{__('Update')}}</button>
                </form>
            </div>
        </div>
    </div>
@endsection