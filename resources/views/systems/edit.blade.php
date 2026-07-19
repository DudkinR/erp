@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h1 class="h3 mb-4">Редагування системи</h1>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card shadow-sm border-0 rounded-3">
        <div class="card-body">
            <form action="{{ route('systems.update', $system->id) }}" method="POST">
                @csrf
                @method('PUT')

                <!-- Основні поля -->
                <div class="row mb-3">
                    <div class="col-md-2">
                        <label for="uk" class="form-label">Назва (укр)</label>
                        <input type="text" name="uk" id="uk" class="form-control" 
                               value="{{ old('uk', $system->uk) }}" required>
                    </div>
                    <div class="col-md-2">
                        <label for="ru" class="form-label">Назва (рос)</label>
                        <input type="text" name="ru" id="ru" class="form-control" 
                               value="{{ old('ru', $system->ru) }}" required>
                    </div>
                    <div class="col-md-2">
                        <label for="en" class="form-label">Назва (англ)</label>
                        <input type="text" name="en" id="en" class="form-control" 
                               value="{{ old('en', $system->en) }}" required>
                    </div>
                    <div class="col-md-2">
                        <label for="abv" class="form-label">Абревіатура</label>
                        <input type="text" name="abv" id="abv" class="form-control" 
                               value="{{ old('abv', $system->abv) }}" required>  
                    </div>
                    <div class="col-md-2">
                        <label for="group" class="form-label">Група</label>
                        <input type="text" name="group" id="group" class="form-control" 
                               value="{{ old('group', $system->group) }}" required>  
                    </div>
                </div>

                <!-- Пошук і мультивибір Divisions -->
                <div class="mb-3">
                    <label class="form-label">Підрозділи</label>
                    <input type="text" id="divisionSearch" class="form-control mb-2" placeholder="Пошук підрозділу...">
                    <div id="divisionList" class="border rounded p-2" style="max-height: 250px; overflow-y: auto;">
                        @foreach($divisions as $division)
                            <div class="form-check">
                                <input class="form-check-input division-checkbox" type="checkbox" 
                                       name="divisions[]" value="{{ $division->id }}"
                                       id="division{{ $division->id }}"
                                       @if(in_array($division->id, old('divisions', $system->divisions->pluck('id')->toArray()))) checked @endif>
                                <label class="form-check-label" for="division{{ $division->id }}">
                                    {{ $division->name }}
                                </label>
                            </div>
                        @endforeach
                    </div>
                </div>

              <div class="mb-3">
                <label for="svb" class="form-label">Об’єкт (Type з slug=Obyekt)</label>
                <input type="text" id="objectSearch" class="form-control mb-2" placeholder="Пошук об’єкта...">

                <select name="svb" id="svb" class="form-select">
                    <option value="">-- виберіть об’єкт --</option>
                    @foreach($Objects as $obj)
                        <option value="{{ $obj->id }}"
                            @if(old('svb', $system->svb ?? '') == $obj->id) selected @endif>
                            {{ $obj->name }}
                        </option>
                    @endforeach
                </select>
            </div>

                <div class="d-flex justify-content-end">
                    <button type="submit" class="btn btn-success">
                        <i class="bi bi-check-lg"></i> Оновити
                    </button>
                    <a href="{{ route('systems.index') }}" class="btn btn-secondary ms-2">Скасувати</a>
                </div>
            </form>
        </div>
    </div>
</div>
{{-- JS для пошуку --}}
<script>
document.addEventListener('DOMContentLoaded', function() {
    // універсальна функція для пошуку
    function setupSearch(inputId, checkboxClass) {
        const searchInput = document.getElementById(inputId);
        const checkboxes = document.querySelectorAll('.' + checkboxClass);

        if (!searchInput) return;

        searchInput.addEventListener('input', function() {
            const query = this.value.toLowerCase();
            checkboxes.forEach(cb => {
                const label = cb.nextElementSibling.textContent.toLowerCase();
                cb.parentElement.style.display = label.includes(query) ? '' : 'none';
            });
        });
    }

    // пошук по підрозділах
    setupSearch('divisionSearch', 'division-checkbox');
    // пошук по об’єктах
    setupSearch('objectSearch', 'object-checkbox');
});
</script>

@endsection
