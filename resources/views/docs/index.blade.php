@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <h1>{{ __('Docs') }}</h1>
            <a href="{{ route('docs.create') }}" class="btn btn-primary mb-3 float-right">{{ __('Create Doc') }}</a>
    </div>
    </div>
    <div class="row border">
        <div class="col-md-12">
           @php $categories = App\Models\Category::where('parent_id', 0)->get(); @endphp
            @foreach ($categories as $category0)
            <div class="row mb-3 border">
                <div class="col-12 border">
                    <h2>{{ $category0->name }}</h2>
                    <h6>{{ __('Add docs to this category') }}</h6>
                    <a href="{{ route('docs.create', ['category_id' => $category0->id]) }}" class="btn border">{{ __('Create Doc') }}</a>
                </div>
                @foreach ($category0->docs as $doc)
                <div class="col-4">
                    <div class="card mb-2">
                        <div class="card-header">{{ $doc->name }}</div>
                        <div class="card-body">
                             <a href="{{ route('docs.show', $doc->id) }}" class="btn btn-primary">{{ __('View') }}</a>
                        </div>
                    </div>
                </div>
                @endforeach
                @php $categories1 = App\Models\Category::where('parent_id', $category0->id)->get(); @endphp
                @foreach ($category0->children as $category1)
                <div class="col-12 ">
                    <h3>{{ $category1->name }}</h3>
                    <h6>{{ __('Add docs to this category') }}</h6>
                    <a href="{{ route('docs.create', ['category_id' => $category1->id]) }}" class="btn border">{{ __('Create Doc') }}</a>
                </div>
                @foreach ($category1->docs as $doc)
                <div class="col-4">
                    <div class="card mb-2">
                        <div class="card-header">{{ $doc->name }}</div>
                        <div class="card-body">
                            <a href="{{ route('docs.show', $doc->id) }}" class="btn btn-primary">{{ __('View') }}</a>
                        </div>
                    </div>
                </div>
                @endforeach
                @endforeach
            </div>
            @endforeach
        </div>
    </div>
    @php $docs_witout_category = App\Models\Doc::where('category_id', NULL)->get(); @endphp
    <div class="row border">
        <div class="col-md-12">
            <h2>{{ __('Docs without category') }}</h2>
            <h6>{{ __('Add docs to this category') }}</h6>
            @foreach ($docs_witout_category as $doc)
            <div class="col-4">
                <div class="card mb-2">
                    <div class="card-header">{{ $doc->name }}</div>
                    <div class="card-body">
                        <a href="{{ route('docs.show', $doc->id) }}" class="btn btn-primary">{{ __('View') }}</a>
                        <a href="{{ route('docs.edit', $doc->id) }}" class="btn btn-warning">{{ __('Edit') }}</a>

                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
@endsection
