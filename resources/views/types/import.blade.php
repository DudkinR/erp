@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h1>{{__('Types import')}}</h1>
                <form method="POST" action="{{ route('types.importData') }}"  enctype="multipart/form-data">
                @include('layouts.import_block')
                    <div class="form-group">
                        <label for="parent_id">{{__('Parent')}}</label>
                        <select class="form-control" id="type_id" name="type_id">
                            <?php $types = \App\Models\Type::orderBy('id', 'desc')->get(); ?> 
                            @foreach($types as $type)
                                <option value="{{ $type->id }}">{{ $type->name }}</option>
                            @endforeach
                        </select>
                        <a href="{{ route('types.create') }}">{{__('Create new parent')}}</a>
                    </div>

                </form>
            </div>
        </div>
    </div>
@endsection