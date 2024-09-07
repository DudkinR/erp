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
                <h1>Edit Goal</h1>
                <form method="POST" action="{{ route('goals.update',$goal) }}">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <input type="hidden" name="_method" value="PUT">
                    <div class="form-group">
                        <label for="title">Title</label>
                        <input type="text" class="form-control" id="name" name="name" value="{{ $goal->name }}">
                    </div>
                    <div class="form-group">
                        <label for="description">Description</label>
                        <textarea class="form-control" id="description" name="description">{!! $goal->description !!} </textarea>
                    </div>
                    <div class="form-group
                        <label for="due_date">Due Date</label>
                        <input type="date" class="form-control" id="due_date" name="due_date" value="{{ $goal->due_date }}">
                    </div>
                    <div class="form-group">
                        <label for="status">Status</label>
                        <select class="form-control" id="status" name="status">
                            <option value="0">Not Started</option>
                            <option value="1">In Progress</option>
                            <option value="2">Complete</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="completed_on">Completed On</label>
                        <input type="date" class="form-control" id="completed_on" name="completed_on">
                    </div>
                    <button type="submit" class="btn btn-primary">Create</button>
                </form>
            </div>
        </div>
    </div>
@endsection