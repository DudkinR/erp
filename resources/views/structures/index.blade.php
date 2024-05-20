@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h1>
                    {{__('Structures')}}
                </h1>
                <a class="text-right" href="{{ route('structure.create') }}">
                    {{__('Create Structure')}}
                    
                </a>
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
                        <div class="col-md-12 bg-primary">
                            
                            <div class="row">
                                @if($structure->positions()->get()->count() == 0)
                                    <div class="col-md-12 bg-primary">
                                    <strong>{{ $structure->name }}</strong>
                                @endif
                                @foreach($structure->positions()->get() as $position)
                                <div class="col-md-2 border  bg-info">
                                        <strong>{{ $position->name }}</strong>
                                        <?php 
                                        $position_id = $position->id;
                                        $personals = App\Models\Personal::where('status', '!=', 'Звільнення')
                                        ->whereHas('positions', function ($query) use ($position_id) {
                                            $query->where('position_id', $position_id);
                                        })
                                        ->get();
                                        ?>
                                        <div class="row">
                                            @if($personals->count() == 0)
                                                <div class="col-md-12 bg-danger">
                                                    <strong>{{__("free") }}</strong>
                                                </div>
                                            @else
                                            @foreach($personals as $personal)
                                                <div class="col-md-12 border  bg-light">
                                                    <strong>{{ $personal->nickname }}</strong>
                                                </div>
                                            @endforeach
                                            @endif
                                                </div>
                                    </div>
                                @endforeach
                            </div>
                            <div class="col-md-12 bg-success">
                                @foreach($structuries as $subStructure)
                                    @if($subStructure->parent_id == $structure->id)
                                        <div class="col-md-12">
                                            
                                            <div class="row">
                                                @if($subStructure->positions()->get()->count() == 0)
                                                    <div class="col-md-12 bg-primary">
                                                        <strong>{{ $subStructure->name }}</strong>
                                                
                                                    </div>
                                                    @endif
                                            @foreach($subStructure->positions()->get() as $position)
                                            <div class="col-md-2 border bg-info">
                                                    <strong>{{ $position->name }}</strong>
                                                    <?php 
                                                    $position_id = $position->id;
                                                    $personals = App\Models\Personal::where('status', '!=', 'Звільнення')
                                                    ->whereHas('positions', function ($query) use ($position_id) {
                                                        $query->where('position_id', $position_id);
                                                    })
                                                    ->get();
                                                    ?>
                                                    <div class="row">
                                                        @if($personals->count() == 0)
                                                            <div class="col-md-12 bg-danger">
                                                                <strong>{{__("free") }}</strong>
                                                            </div>
                                                        @else
                                                        @foreach($personals as $personal)
                                                            <div class="col-md-12 border  bg-light">
                                                                <strong>{{ $personal->nickname }}</strong>
                                                            </div>
                                                        @endforeach
                                                        @endif
                                                        </div>
                                                </div>
                                            @endforeach
                                            </div>
                                            <!-- Добавьте дополнительные подуровни, если необходимо -->
                                            <div class="col bg-warning">
                                                @foreach($structuries as $subStructure1)
                                                    @if($subStructure1->parent_id == $subStructure->id)
                                                        <div class="col-md-12">
                                                            <strong>{{ $subStructure1->name }}</strong>
                                                            <div class="row">
                                                                @if($subStructure1->positions()->get()->count() == 0)

                                                                    <div class="col-md-12 bg-primary">
                                                                        <strong>{{ $subStructure1->name }}</strong>
                                                                        
                                                                       
                                                                    </div>
                                                                @endif
                                                            @foreach($subStructure1->positions()->get() as $position)
                                                                <div class="col-md-2 border  bg-info">
                                                                    <strong>{{ $position->name }}</strong>
                                                                    <?php
                                                                    $position_id = $position->id;
                                                                    $personals = App\Models\Personal::where('status', '!=', 'Звільнення')
                                                                    ->whereHas('positions', function ($query) use ($position_id) {
                                                                        $query->where('position_id', $position_id);
                                                                    })
                                                                    ->get();
                                                                    ?>
                                                                    <div class="row">
                                                                        @if($personals->count() == 0)
                                                                            <div class="col-md-12 bg-danger">
                                                                                <strong>{{__("free") }}</strong>
                                                                            </div>
                                                                        @else
                                                                        @foreach($personals as $personal)
                                                                            <div class="col-md-12 border  bg-light">
                                                                                <strong>{{ $personal->nickname }}</strong>
                                                                            </div>
                                                                        @endforeach
                                                                        @endif
                                                                        </div>
                                                                        
                                                                </div>
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
                    @endif
                @endforeach
            </div>
        </div>

    </div>
@endsection