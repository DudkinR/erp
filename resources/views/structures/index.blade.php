@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h1>
                    {{__('Structures')}}
                </h1>
                @if(Auth::user()->hasRole('admin'))
                <a class="text-right" href="{{ route('structure.create') }}">
                    {{__('Create Structure')}}
                    
                </a>
                @endif
            </div>
        </div>
        <div class="row">
<style>
 .tree-structure ul {
    padding-top: 20px; 
    position: relative;
    transition: all 0.5s;
    list-style-type: none;
    padding-left: 20px;
}

.tree-structure li {
    position: relative;
    padding: 10px 0 10px 20px;
    border-left: 2px solid #ccc; /* Вертикальна лінія зв'язку */
}

/* Горизонтальна лінія до кожного елемента */
.tree-structure li::before {
    content: '';
    position: absolute; 
    top: 24px; 
    left: 0;
    width: 15px; 
    height: 2px;
    background: #ccc;
}

/* Прибираємо залишок лінії у останнього елемента */
.tree-structure li:last-child {
    border-left: none;
}
.tree-structure li:last-child::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    width: 2px;
    height: 25px;
    background: #ccc;
}

/* Стиль карток для посад та людей */
.struct-node {
    background: #fff;
    border: 1px solid #ddd;
    border-radius: 6px;
    padding: 12px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.05);
    display: inline-block;
    min-width: 250px;
}
.pos-badge {
    background-color: #e3f2fd;
    color: #0d47a1;
    padding: 3px 8px;
    border-radius: 4px;
    font-size: 0.85rem;
}
.person-badge {
    background-color: #f8f9fa;
    border: 1px solid #e0e0e0;
    padding: 4px;
    margin-top: 5px;
    border-radius: 4px;
}

</style>
         <div class="container my-4">
    <div class="tree-structure">
        <ul>
            <!-- КОРЕНЕВИЙ РІВЕНЬ (parent_id == 0) -->
            @foreach($structuries as $structure)
                @if($structure->parent_id == 0)
                    <li>
                        <div class="struct-node mb-3">
                            <h5 class="text-primary mb-2">
                                📁 {{ $structure->name }}
                                @if(Auth::user()->hasRole('admin'))
                                    <a href="{{ route('structure.edit', $structure->id) }}" class="btn btn-sm btn-link p-0">+</a>
                                @endif
                            </h5>
                            
                            <!-- Посади та працівники кореневого рівня -->
                           @include('structures.nodes', ['structure' => $structure])

                        </div>

                        <!-- ПІДРІВЕНЬ 1 -->
                        @if($structuries->where('parent_id', $structure->id)->count() > 0)
                            <ul>
                                @foreach($structuries as $subStructure)
                                    @if($subStructure->parent_id == $structure->id)
                                        <li>
                                            <div class="struct-node mb-2">
                                                <h6 class="text-success mb-2">
                                                    └── 📁 {{ $subStructure->name }}
                                                    @if(Auth::user()->hasRole('admin'))
                                                        <a href="{{ route('structure.edit', $subStructure->id) }}" class="btn btn-sm btn-link p-0">+</a>
                                                    @endif
                                                </h6>
                                               @include('structures.nodes', ['structure' => $subStructure])

                                            </div>

                                            <!-- ПІДРІВЕНЬ 2 -->
                                            @if($structuries->where('parent_id', $subStructure->id)->count() > 0)
                                                <ul>
                                                    @foreach($structuries as $subStructure1)
                                                        @if($subStructure1->parent_id == $subStructure->id)
                                                            <li>
                                                                <div class="struct-node">
                                                                    <span class="text-warning font-weight-bold">└── 📁 {{ $subStructure1->name }}</span>
                                                                    @if(Auth::user()->hasRole('admin'))
                                                                        <a href="{{ route('structure.edit', $subStructure1->id) }}" class="btn btn-sm btn-link p-0">+</a>
                                                                    @endif
                                                                    @include('structures.nodes', ['structure' => $subStructure1])

                                                                </div>
                                                            </li>
                                                        @endif
                                                    @endforeach
                                                </ul>
                                            @endif

                                        </li>
                                    @endif
                                @endforeach
                            </ul>
                        @endif

                    </li>
                @endif
            @endforeach
        </ul>
    </div>
</div>


    </div>
@endsection