@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h1>{{__('category')}}</h1>
                <form method="POST" action="{{ route('cats.update',$category) }}" enctype="multipart/form-data">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <input type="hidden" name="_method" value="PUT">
                    <div class="form-group">
                        <label for="title">
                            {{__('Name')}}
                            </label>
                        <input type="text" class="form-control" id="name" name="name" value="{{ $category->name }}">
                    </div>
                    <div class="form-group">
                        <label for="description">{{__('Description')}}</label>
                        <textarea class="form-control" id="description" name="description">{!! $category->description !!} </textarea>
                    </div>
                    <div class="form-group">
                        <label for="slug">{{__('Slug')}}</label>
                        <input type="text" class="form-control" id="slug" name="slug" value="{{ $category->slug }}">
                    </div>
                    <div id="image_preview" class="form-group"></div>
                    <div class="form-group">
                        <label for="image">{{__('Image')}}</label>
                        <input type="file" class="form-control" id="image" name="image">
                    </div>
                    <div class="form-group">
                        <label for="parent_id">{{__('Parent')}}</label>
                        <select class="form-control" id="parent_id" name="parent_id">
                            <option value="0" selected>{{__('First')}}</option>
                            <?php
                            $cats = \App\Models\Category::all();
                            ?>
                            @foreach($cats as $cat)
                                <option value="{{$cat->id}}" @if($category->parent_id == $cat->id) selected @endif>{{$cat->name}}</option>
                            @endforeach
                        </select>

                       
                    </div>
                    <button type="submit" class="btn btn-primary">
                        {{__('Update')}}
                    </button>
                </form>
            </div>
        </div>
    </div>
    <script>
        document.getElementById("image").addEventListener("change", function (event) {
            var image_preview = document.getElementById("image_preview");
            while (image_preview.firstChild) {
                image_preview.removeChild(image_preview.firstChild);
            }
            for (var i = 0; i < event.target.files.length; i++) {
                var img = document.createElement("img");
                img.src = URL.createObjectURL(event.target.files[i]);
                img.style.maxWidth = "300px";
                img.style.maxHeight = "300px";
                image_preview.appendChild(img);
            }
        });
    </script>
@endsection