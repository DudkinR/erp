@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h1>{{__('nomenclatures')}}</h1>
                <form method="POST" action="{{ route('nomenclaturs.importData') }}"  enctype="multipart/form-data">
                @include('layouts.import_block')
                    <div class="form-group row">
                        <label for="type_id" class="col-md-4 col-form-label text-md-right">{{__('Type')}}</label>
                        <div class="col-md-6">
                            <select class="form-control" id="type_id" name="type_id">
                                <option value="">{{__('Select type')}}</option>
                                <?php $types = \App\Models\Type::all(); ?>
                                @foreach($types as $type)
                                    <option value="{{ $type->id }}">{{ $type->name }}</option>
                                @endforeach
                            </select>
                            <a href="{{ route('types.create') }}">{{__('Create new type')}}</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection