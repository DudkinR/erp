@extends('layouts.app')
@section('content')
    <div class="container">
        @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif
        <div class="row">
            <div class="col-md-12">
                <h1>{{__('New Facts')}}
                    </h1>
                <form method="POST" action="{{ route('facts.store') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group">
                        <label for="title">{{__('Title')}}</label>
                        <input type="text" class="form-control" id="name" name="name">
                    </div>
                    <div class="form-group">
                        <label for="description">{{__('Description')}}</label>
                        <textarea class="form-control" id="description" name="description" rows="10" ></textarea>
                    </div>
                    <div id="image_preview" class="form-group"></div>
                    <div class="form-group">
                        <label for="image">{{__('Image')}}</label>
                        <input type="file" class="form-control" id="image" name="image">
                    </div>
                    <div class="form-group">
                        <label for="status">{{__('Status')}}</label>
                        <select class="form-control" id="status" name="status">
                            <option value="0">{{__('Not Started')}}</option>
                             <option value="active">{{__('In Progress')}}</option>
                            <option value="freeze">{{__('Freeze')}}</option>
                            <option value="inactive">{{__('Inactive')}}</option>
                            <option value="completed">{{__('Complete')}}</option>   
                            <option value="closed">{{__('Closed')}}</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary">{{__('Create')}}</button>
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