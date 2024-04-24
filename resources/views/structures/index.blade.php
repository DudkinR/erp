@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h1>Structures</h1>
                <a class="text-right" href="{{ route('structure.create') }}">Create Structure</a>
            </div>
        </div>
        <div class="row">
<style>
    .structure {
    font-family: Arial, sans-serif;
}

.top-level {
    background-color: #f2f2f2;
    padding: 10px;
    margin-bottom: 10px;
}

.sub-structure {
    margin-left: 20px;
}

.sub-level {
    background-color: #e6e6e6;
    padding: 5px;
    margin-top: 5px;
    margin-bottom: 5px;
}
.sub-structure2 {
    margin-left: 20px;
}
.sub-level2{
    background-color: #666;
    padding: 5px;
    margin-top: 5px;
    margin-bottom: 5px;
}

</style>
            <div class="container">
                @foreach($structuries as $structure)
                    @if($structure->parent_id == 0)
                        <div class="col bg-light">
                            <strong>{{ $structure->name }}</strong>
                            <div class="col bg-info">
                                @foreach($structuries as $subStructure)
                                    @if($subStructure->parent_id == $structure->id)
                                        <div class="col">
                                            <strong>{{ $subStructure->name }}</strong>
                                            <!-- Добавьте дополнительные подуровни, если необходимо -->
                                            <div class="col bg-warning">
                                                @foreach($structuries as $subStructure1)
                                                    @if($subStructure1->parent_id == $subStructure->id)
                                                        <div class="col">
                                                            <strong>{{ $subStructure1->name }}</strong>
                                                        </div>
                                                    @endif
                                                @endforeach
                                                </div>
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                    @endif
                @endforeach
            </div>
        </div>

    </div>
@endsection