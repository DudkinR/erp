@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h1>{{__('New cats')}}
                    </h1>
                <form method="POST" action="{{ route('cats.store') }}" enctype="multipart/form-data">
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
                        <label for="slug">{{__('Slug')}}</label>
                        <input type="text" class="form-control" id="slug" name="slug">
                    </div>
                    @include('components.input_file_image')
                    <div class="form-group">
                        <label for="parent_id">{{__('Parent')}}</label>
                        <select class="form-control" id="status" name="status">
                            <option value="0" selected >{{__('First')}}</option>
                            <?php
                            $cats = \App\Models\Category::all();
                            if(isset($_GET['parent_id'])){
                                $parent = $_GET['parent_id'];
                            }
                            else {
                                $parent = 0;
                            }
                            ?>
                            @foreach($cats as $cat)
                                <option value="{{$cat->id}}"
                                    @if($cat->id == $parent)
                                    selected
                                    @endif
                                >{{$cat->name}}</option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary">{{__('Create')}}</button>
                </form>
            </div>
        </div>
    </div>
    <script>

    </script>
@endsection