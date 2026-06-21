@extends('layouts.app')
@section('content')
<div class="container">
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
        </div>
    @endif
    @if(session('success'))
        <div class="alert alert-success">{{ __(session('success')) }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ __(session('error')) }}</div>
    @endif

    <h1>{{__('Division')}}</h1>
    <form method="POST" action="{{ route('divisions.update',$division) }}">
        @csrf
        @method('PUT')

        <!-- Name -->
        <div class="form-group mb-3">
            <label for="name">{{__('Name')}}</label>
            <input type="text" class="form-control" id="name" name="name" value="{{ $division->name }}">
        </div>

        <!-- Description -->
        <div class="form-group mb-3">
            <label for="description">{{__('Description')}}</label>
            <textarea class="form-control" id="description" name="description">{{ $division->description }}</textarea>
        </div>

        <!-- Abbreviation -->
        <div class="form-group mb-3">
            <label for="abv">{{__('Abbreviation')}}</label>
            <input type="text" class="form-control" id="abv" name="abv" value="{{ $division->abv }}">
        </div>

        <!-- Slug -->
        <div class="form-group mb-3">
            <label for="slug">{{__('Slug')}}</label>
            <input type="text" class="form-control" id="slug" name="slug" value="{{ $division->slug }}">
        </div>

        <!-- Parent Division -->
        <x-search-multiselect
            id="parent_id"
            name="parent_id"
            label="Parent Division"
            :data="$parents"
            :selected="[$division->parent_id]"
        />

        <!-- Positions -->
        <x-search-multiselect
            id="positions"
            name="positions[]"
            label="Positions"
            :data="$positions"
            :selected="$division->positions->pluck('id')->toArray()"
        />

        <!-- Kndk -->
        <x-search-multiselect
            id="kndks"
            name="kndk_ids[]"
            label="KNDK (СОУ НАЕК 180:2020)"
            :data="$kndks"
            :selected="$division->kndks->pluck('id')->toArray()"
        />

        <button type="submit" class="btn btn-primary">{{__('Update')}}</button>
    </form>
</div>
@endsection
