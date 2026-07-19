@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h1 class="h3 mb-4">Класифікатор</h1>
    <!-- Кнопка додавання нового елемента -->
    @if(Auth::user()->hasRole('admin'))
    <div class="mb-3">
        <a href="{{ route('systems.create') }}" class="btn btn-primary">Додати новий елемент</a>
    </div>
    @endif

    <!-- Панель пошуку -->
    <div class="card shadow-sm border-0 rounded-3 mb-3">
        <div class="card-body p-3">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <div class="input-group">
                        <span class="input-group-text bg-white border-end-0 text-muted">
                            <i class="bi bi-search"></i>
                        </span>
                        <input type="text" id="searchInput" class="form-control border-start-0 ps-0" placeholder="Пошук за назвою чи абревіатурою...">
                    </div>
                </div>
                <div class="col-md-4 text-end mt-2 mt-md-0">
                    <span class="text-muted small" id="searchResultCount">Всього: {{ count($items) }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Таблиця -->
    <div class="card shadow-sm border-0 rounded-3">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light text-uppercase fs-7 text-muted border-bottom">
                        <tr>
                            <th class="ps-4">Абревіатура</th>
                            <th>Назва (укр)</th>
                            <th>Назва (рос)</th>
                            <th>Назва (англ)</th>
                            <th>Група</th>
                            <th>{{_('Divisions')}}</th>
                            <th>Сфера/вид</th>
                            @if(Auth::user()->hasRole('admin'))
                            <th class="text-end pe-4">Дії</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody id="tableBody">
                        @foreach($items as $item)
                        <tr>
                            <td class="ps-4">{{ $item->abv }}</td>
                            <td>{{ $item->uk }}</td>
                            <td>{{ $item->ru }}</td>
                            <td>{{ $item->en }}</td>
                            <td>{{ $item->group }}</td>
                           <td>
                                @foreach($item->divisions as $division)
                                    {{ $division->name }}{{ !$loop->last ? ', ' : '' }}
                                @endforeach
                            </td>
                            <td>
                                {{ $item->object->name ?? '' }}
                            </td>

                            @if(Auth::user()->hasRole('admin'))
                            <td class="text-end pe-4">
                                <a href="{{ route('systems.edit', $item->id) }}" class="btn btn-sm btn-outline-secondary">Редагувати</a>
                                <form action="{{ route('systems.destroy', $item->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Видалити {{ $item->abv }}?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger">Видалити</button>
                                </form>
                            </td>
                            @endif
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

{{-- Дані для JS --}}
<script>
    window.rawData = @json($items);
    window.divisions = @json($divisions);
    window.Objects = @json($Objects);
</script>

{{-- Скрипт пошуку --}}
<script>

document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchInput');
    const tableBody = document.getElementById('tableBody');
    const resultCountSpan = document.getElementById('searchResultCount');
    const allItems = window.rawData || [];

    function renderTable(items) {
        tableBody.innerHTML = '';
        if (items.length === 0) {
            tableBody.innerHTML = `<tr><td colspan="7" class="text-center py-4 text-muted">❌ Нічого не знайдено</td></tr>`;
            resultCountSpan.textContent = 'Знайдено: 0';
            return;
        }
        items.forEach(item => {
            tableBody.innerHTML += `
                <tr>
                    <td class="ps-4">${item.abv || ''}</td>
                    <td>${item.uk || ''}</td>
                    <td>${item.ru || ''}</td>
                    <td>${item.en || ''}</td>
                    <td>${item.group || ''}</td>
                   <td>
                        ${(item.divisions || []).map(d => d.name).join(', ')}
                    </td>
                    <td>
                        ${item.object ? item.object.name : ''}
                    </td>

                    @if(Auth::user()->hasRole('admin'))
                    <td class="text-end pe-4">
                        <a href="/systems/${item.id}/edit" class="btn btn-sm btn-outline-secondary">Редагувати</a>
                        <form action="/systems/${item.id}" method="POST" class="d-inline" onsubmit="return confirm('Видалити ${item.abv}?');">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <input type="hidden" name="_method" value="DELETE">
                            <button type="submit" class="btn btn-sm btn-outline-danger">Видалити</button>
                        </form>
                    </td>
                    @endif
                </tr>
            `;
        });
        resultCountSpan.textContent = `Всього: ${items.length}`;
    }

    function filterItems(query) {
        if (!query) return allItems;
        query = query.toLowerCase().trim();
        return allItems.filter(item => {
            return (item.uk && item.uk.toLowerCase().includes(query)) ||
                   (item.ru && item.ru.toLowerCase().includes(query)) ||
                   (item.en && item.en.toLowerCase().includes(query)) ||
                   (item.abv && item.abv.toLowerCase().includes(query)) ||
                   (item.group && item.group.toLowerCase().includes(query)) ||
                   (item.svb && item.svb.toLowerCase().includes(query));
        });
    }

    searchInput.addEventListener('input', function(e) {
        const filtered = filterItems(e.target.value);
        renderTable(filtered);
    });

    renderTable(allItems);
});
</script>
@endsection
