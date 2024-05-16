@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h1>{{__('New Document')}}
                    </h1>
                <a href="{{ route('docs.index') }}" class="btn btn-secondary mb-3">{{__('Back')}}</a>
                <form method="POST" action="{{ route('docs.store') }}" enctype="multipart/form-data">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <div class="form-group">
                        <label for="title">{{__('Title')}}</label>
                        <input type="text" class="form-control" id="name" name="name">
                    </div>
                    <div class="form-group">
                        <label for="description">{{__('Description')}}</label>
                        <textarea class="form-control" id="description" name="description" rows="10" ></textarea>
                    </div>
                    
                    <div class="form-group">
                        <label for="file">{{__('File')}}</label>
                        <input type="file" class="form-control" id="file" name="file">
                    </div>
                    <div class="form-group">
                        <label for="category">{{__('Category of document')}}</label>
                        <select class="form-control" id="category_id" name="category_id">
                            <?php $categories = App\Models\Category ::where('parent_id', '>=',0)->get(); ?>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}"
                                @if(isset($_GET['category_id']) && $_GET['category_id'] == $category->id)
                                    selected
                                @endif  
                                >{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="file_name">{{__('File Name')}}</label>
                        <input type="text" class="form-control" id="file_name" name="file_name">
                    </div>
                    <div class="form-group">
                        <label for="link">{{__('Link other resource')}}</label>
                        <input type="text" class="form-control" id="link" name="link">
                    </div>
                    <div class="form-group">
                        <label for="slug">{{__('Slug')}}</label>
                        <input type="text" class="form-control" id="slug" name="slug">
                    </div>
                    <div class="form-group">
                        <label for="revision_date">{{__('Revision Date')}}</label>
                        <input type="date" class="form-control" id="revision_date" name="revision_date">
                    </div>
                    <div class="form-group">
                        <label for="publication_date">{{__('Publication Date')}}</label>
                        <input type="date" class="form-control" id="publication_date" name="publication_date">
                    </div>
                    <div class="form-group">
                        <label for="creation_date">{{__('Creation Date')}}</label>
                        <input type="date" class="form-control" id="creation_date" name="creation_date">
                    </div>
                    <div class="form-group">
                        <label for="deletion_date">{{__('Deletion Date')}}</label>
                        <input type="date" class="form-control" id="deletion_date" name="deletion_date">
                    </div>
                    <div class="form-group">
                        <label for="last_change_date">{{__('Last Change Date')}}</label>
                        <input type="date" class="form-control" id="last_change_date" name="last_change_date">
                    </div>
                    <div class="form-group">
                        <label for="last_view_date">{{__('Last View Date')}}</label>
                        <input type="date" class="form-control" id="last_view_date" name="last_view_date">
                    </div>
                    <div class="form-group">
                        <label for="status">{{__('Status')}}</label>
                        <select class="form-control" id="status" name="status">
                            <option value="0">{{__('Draft')}}</option>
                            <option value="1">{{__('Published')}}</option>
                            <option value="2">{{__('Overdue')}}</option>
                            <option value="3">{{__('Archived')}}</option>
                            <option value="4">{{__('Reference')}}</option>
                            <option value="5">{{__('Deleted')}}</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="document_releted">{{__('Document Releted')}} 
                            <input type="text" id="find_document" placeholder="{{__('Find document')}}" onkeyup="findDocument()">
                            <button type="button" class="btn border" onclick="addDraftDocument()">{{__('Add draft')}}</button>
                        </label>
                        <?php $docs = App\Models\Doc::all(); ?>
                        <select class="form-control" id="document_releted" name="document_releted[]" multiple>
                            @foreach($docs as $doc)
                                <option value="{{ $doc->id }}">{{ $doc->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="lng">{{__('Language')}}</label>
                        <select class="form-control" id="lng" name="lng">
                        <option value="uk" selected>{{__('Ukrainian')}}</option>
                            <option value="ru">{{__('Russian')}}</option>
                            <option value="en">{{__('English')}}</option>
                        </select>
                    </div>

                    <button type="submit" class="btn btn-primary">{{__('Create')}}</button>
                </form>
            </div>
        </div>
    </div>
    <script>
        const docs= @json($docs);
     
    </script>
@endsection