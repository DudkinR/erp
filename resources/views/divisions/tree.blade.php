<ul>
    <li>
        <strong>{{ $division->name }}</strong>

        {{-- Кнопки управління --}}
        <div class="btn-group btn-group-sm mb-2">
            <button class="btn btn-primary" 
                    data-bs-toggle="modal" 
                    data-bs-target="#editDivisionModal" 
                    data-id="{{ $division->id }}">
                ✏️ 
            </button>
            <button class="btn btn-danger" 
                    onclick="deleteDivision({{ $division->id }})">
                🗑️ 
            </button>
            <button class="btn btn-success" 
                    data-bs-toggle="modal" 
                    data-bs-target="#addDivisionModal" 
                    data-parent="{{ $division->id }}">
                ➕ 
            </button>
        </div>

        {{-- Посади --}}
        <ul>
            @foreach($division->positions as $position)
                <li>
                    {{ $position->name }}
                    <div class="btn-group btn-group-sm mb-2">
                        <button class="btn btn-primary" 
                                data-bs-toggle="modal" 
                                data-bs-target="#editPositionModal" 
                                data-id="{{ $position->id }}">
                            ✏️
                        </button>
                        <button class="btn btn-danger" 
                                onclick="deletePosition({{ $position->id }})">
                            🗑️
                        </button>
                        
                    </div>

                    <ul>
                        @foreach($position->personals as $person)
                            <li>
                                {{ $person->fio }}
                                <div class="btn-group btn-group-sm">
                                    <button class="btn btn-primary" 
                                            data-bs-toggle="modal" 
                                            data-bs-target="#editPersonModal" 
                                            data-id="{{ $person->id }}">
                                        ✏️
                                    </button>
                                    <button class="btn btn-danger" 
                                            onclick="deletePerson({{ $person->id }})">
                                        🗑️
                                    </button>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                </li>
            @endforeach
            <li>
                <button class="btn btn-success" 
                                data-bs-toggle="modal" 
                                data-bs-target="#addPersonModal" 
                               ">
                            ➕ 
                        </button>
            </li>
        </ul>

        {{-- Дочірні підрозділи --}}
        @foreach($division->children as $child)
            @include('divisions.tree', ['division' => $child])
        @endforeach
    </li>
</ul>
