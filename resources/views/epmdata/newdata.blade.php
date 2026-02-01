@extends('layouts.app')
@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">

            <h1 class="mb-3 text-center text-secondary fw-bold">
                @if(!$division)
                    {{__('EPM')}}
                @else
                    {{$division->name}}
                @endif
            </h1>

            <h2 class="text-center text-muted">
                {{__('Data for')}} : <span class="badge bg-light text-dark">{{ $date }}</span>
            </h2>

            <a href="{{ route('epmdata') }}" class="btn btn-outline-secondary mb-4 w-100">
                {{__('Back to Data')}}
            </a>

            <form method="POST" action="{{ route('epmdata.loadupdate',$date) }}">
                @csrf
                @method('PUT')
                <input type="hidden" name="date" value="{{ $date }}">

                @foreach($epmdatas as $epmdata)
                    <div class="card mb-3 shadow-sm">
                        <div class="card-body 
                            @if($epmdata->value == null) bg-light text-muted 
                            @else bg-white 
                            @endif">

                            <label for="value_{{$epmdata->epm_id}}" class="fw-bold">
                                {{ $epm->name }}
                            </label>

                            <input type="text"
                                   class="form-control mt-1"
                                   id="value_{{$epmdata->epm_id}}"
                                   name="value[{{ $epmdata->id }}]"
                                   value="{{ old('value.' . $epmdata->id, $epmdata->value) }}"
                                   placeholder="{{__('Enter value')}}">

                            <small class="text-muted d-block mt-2">
                                Min: <b>{{ $epm->min }}</b> &nbsp; | &nbsp;
                                Max: <b>{{ $epm->max }}</b>
                            </small>

                            <div class="mt-2">
                                <span id="short_{{ $epmdata->epm_id }}" class="text-muted">
                                    {{ Str::limit($epm->description, 50) }}
                                </span>

                                <a href="#" class="text-primary ms-2"
                                   data-bs-toggle="collapse"
                                   data-bs-target="#desc_{{ $epmdata->epm_id }}">
                                    {{ __('Детальніше') }}
                                </a>

                                <div id="desc_{{ $epmdata->epm_id }}" class="collapse mt-2 text-secondary">
                                    {!! nl2br(e($epm->description)) !!}
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach

                <button type="submit" class="btn btn-primary w-100 mt-3">
                    {{__('Update')}}
                </button>
            </form>
        </div>
    </div>
</div>
@endsection