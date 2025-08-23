@extends('layouts.app')

@section('content')

<div class="container mt-4">
    <h2 class="mb-4 text-center">Налаштування типів документів</h2>
    {{-- Форма для додавання нового типу --}}
    <div class="card">
        <div class="card-header bg-success text-white">
            Додати новий тип
        </div>
        <div class="card-body">
            <form method="POST" action="{{ url('/archived-setting-upd') }}">
                @csrf
                <div class="mb-3">
                    <label for="name" class="form-label">Назва</label>
                    <input type="text" class="form-control" id="name" name="name" placeholder="Введіть назву" value="{{ old('name') }}">
                </div>
                 <div class="mb-3">
                    <label for="foreing" class="form-label">Назва</label>
                    <input type="text" class="form-control" id="foreing" name="foreing" placeholder="Іноземну назву" value="{{ old('foreing') }}">
                </div>
                <div class="mb-3">
                    <label for="parent_id" class="form-label">Від</label>
                        @php
                            // значення з old() або з моделі
                            $selectedParent = old('parent_id', $model->parent_id ?? null);
                        @endphp

                        <select class="form-select" id="parent_id" name="parent_id" required>
                            @if($parent_type_doc)
                                <option value="{{ $parent_type_doc }}"
                                    @if($selectedParent == $parent_type_doc) selected @endif>
                                    Документ
                                </option>
                            @endif

                            @if($parent_type_Developer)
                                <option value="{{ $parent_type_Developer }}"
                                    @if($selectedParent == $parent_type_Developer) selected @endif>
                                    Розробник
                                </option>
                            @endif

                            @if($parent_type_Contractor)
                                <option value="{{ $parent_type_Contractor }}"
                                    @if($selectedParent == $parent_type_Contractor) selected @endif>
                                    Виконавець
                                </option>
                            @endif

                            @if($parent_type_object)
                                <option value="{{ $parent_type_object }}"
                                    @if($selectedParent == $parent_type_object) selected @endif>
                                    Об'єкт
                                </option>
                            @endif
                            @if($archiveTypes)
                                <option value="{{ $parent_type_arhive }}"
                                    @if($selectedParent == $parent_type_arhive) selected @endif>
                                    Архів
                                </option>
                            @endif
                        </select>
                </div>
                <button type="submit" class="btn btn-success">Додати</button>
            </form>
        </div>
    </div>
    {{-- Вивід існуючих типів --}}
    <div class="card mb-5">
        <div class="card-header bg-primary text-white">
            Присутні типи
        </div>
        <div class="card-body">
            <table class="table table-bordered table-hover">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Назва</th>
                        <th>Категорія</th>
                        <th>Дія</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($docs as $type)
                        <tr class="bg-light">
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $type->name }}</td>
                            <td>Документ</td>
                            <td>
                                <form action="{{ route('types.destroy',  $type) }}" 
                                        method="POST" 
                                        class="d-inline"
                                        onsubmit="return confirm('Ви впевнені, що хочете видалити цей тип?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger">
                                        <i class="bi bi-trash"></i> Видалити
                                    </button>
                                </form>
                            </td>

                        </tr>
                    @endforeach
                    @foreach($Developers as $type)
                        <tr class="bg-info">
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $type->name }}</td>
                            <td>Розробник</td>
                            <td>
                                <form action="{{ route('types.destroy',  $type) }}" 
                                        method="POST" 
                                        class="d-inline"
                                        onsubmit="return confirm('Ви впевнені, що хочете видалити цей тип?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger">
                                        <i class="bi bi-trash"></i> Видалити
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                    @foreach($Contractors as $type)
                        <tr class="bg-warning">
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $type->name }}</td>
                            <td>Виконавець</td>
                            <td>
                                <form action="{{ route('types.destroy',  $type) }}" 
                                        method="POST" 
                                        class="d-inline"
                                        onsubmit="return confirm('Ви впевнені, що хочете видалити цей тип?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger">
                                        <i class="bi bi-trash"></i> Видалити
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                    @foreach($objects as $type)
                        <tr style="background-color: #adb4bbff;">
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $type->name }}</td>
                            <td>Об'єкт</td>
                            <td>
                                <form action="{{ route('types.destroy',  $type) }}" 
                                        method="POST" 
                                        class="d-inline"
                                        onsubmit="return confirm('Ви впевнені, що хочете видалити цей тип?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger">
                                        <i class="bi bi-trash"></i> Видалити
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                    @foreach($archiveTypes as $type)
                        <tr class="bg-secondary">
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $type->name }}</td>
                            <td>Архів</td>
                            <td>
                                <form action="{{ route('types.destroy',  $type) }}" 
                                        method="POST" 
                                        class="d-inline"
                                        onsubmit="return confirm('Ви впевнені, що хочете видалити цей тип?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger">
                                        <i class="bi bi-trash"></i> Видалити
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>


</div>

</div> 
@endsection
    