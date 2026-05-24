@extends('layouts.app')
@section('content')

<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-1">Класифікатор СОУ НАЕК 180:2020</h1>
            <p class="text-muted mb-0">Електронний реєстр сфер управління, напрямів та видів діяльності</p>
        </div>
        {{-- Кнопка створення, якщо потрібна --}}
        <a href="{{route('kndks.create')}}" class="btn btn-primary">
            <i class="bi bi-plus-lg"></i> Додати елемент
        </a>
    </div>

    <!-- Картка з таблицею -->
    <div class="card shadow-sm border-0 rounded-3">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light text-uppercase fs-7 text-muted border-bottom">
                        <tr>
                            <th class="ps-4" style="width: 15%;">Цифровий код</th>
                            <th style="width: 12%;">Рівень</th>
                            <th style="width: 45%;">Найменування</th>
                            <th style="width: 15%;">Об'єкт</th>
                            <th class="pe-4 text-end" style="width: 13%;">Дії</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($kndks as $item)
                            {{-- Визначення стилю рядка залежно від рівня через ваш кастомний атрибут getLevelAttribute() --}}
                            <tr class="{{ $item->level === 1 ? 'fw-bold table-light border-top-2' : ($item->level === 2 ? 'bg-white' : 'text-secondary bg-light bg-opacity-10') }}">
                                
                                <!-- Код з відступами для візуальної вкладеності -->
                                <td class="ps-4 font-monospace">
                                    @if($item->level === 2) &nbsp;&nbsp;&nbsp; @endif
                                    @if($item->level === 3) &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; @endif
                                    <span class="badge {{ $item->level === 1 ? 'bg-dark' : ($item->level === 2 ? 'bg-secondary' : 'bg-light text-dark border') }}">
                                        {{ $item->full_code }}
                                    </span>
                                </td>

                                <!-- Бейдж рівня ієрархії -->
                                <td>
                                    @if($item->level === 1)
                                        <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-2">І. Клас</span>
                                    @elseif($item->level === 2)
                                        <span class="badge bg-info-subtle text-info border border-info-subtle px-2">ІІ. Підклас</span>
                                    @else
                                        <span class="badge bg-warning-subtle text-warning border border-warning-subtle px-2">ІІІ. Група</span>
                                    @endif
                                </td>

                                <!-- Назва елемента -->
                              <td>
                                <span class="{{ $item->level === 1 ? 'fs-5' : 'fs-6' }}">
                                    @php
                                        // Розбиваємо текст на масив рядків за символом переносу
                                        $lines = explode("\n", e($item->name));
                                        // Беремо перший рядок
                                        $firstLine = array_shift($lines);
                                    @endphp

                                    <!-- Перший рядок робимо жирним -->
                                    <strong class="fw-bold d-block mb-1">{!! $firstLine !!}</strong>

                                    <!-- Решту рядків виводимо звичайним текстом через <br> -->
                                    @if(count($lines) > 0)
                                        <span class="text-muted small d-block">
                                            {!! implode('<br>', $lines) !!}
                                        </span>
                                    @endif

                                </span>
                                 <a href="{{route('kndks.show',$item)}}" class="btn btn-outline-secondary border-0" title="Редагувати">
                                            Дивитись
                                        </a>
                            </td>

                                <!-- Тип об'єкта класифікації (пункт 5.1.2) -->
                                <td>
                                    @if($item->object_type)
                                        <span class="badge text-dark bg-opacity-10 
                                            @if($item->object_type == 'документ') bg-success 
                                            @elseif($item->object_type == 'функція') bg-primary 
                                            @else bg-danger @endif">
                                            {{ Str::ucfirst($item->object_type) }}
                                        </span>
                                    @else
                                        <span class="text-muted fs-7">—</span>
                                    @endif
                                </td>

                                <!-- Кнопки керування -->
                                <td class="pe-4 text-end">
                                    <div class="btn-group btn-group-sm" role="group">
                                        <a href="{{route('kndks.edit',$item)}}" class="btn btn-outline-secondary border-0" title="Редагувати">
                                            Редагувати
                                        </a>  
                                        <a href="{{route('kndks.importPage',$item)}}" class="btn btn-outline-secondary border-0" title="Імпорт">
                                           Імпорт докс {{$item->documents_count}}
                                        </a>
                                       
                                        
                                      <!-- Форма Видалення -->
                                        <form action="{{ route('kndks.destroy', $item->id) }}" method="POST" 
                                            onsubmit="return confirm('Ви впевнені, що хочете видалити елемент {{ $item->full_code }}?');" 
                                            style="display: inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-outline-danger border-0" title="Видалити">
                                                Видалити
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-5 text-muted">
                                    <i class="bi bi-folder-x display-4 d-block mb-2"></i>
                                    Класифікатор порожній. Додайте перший рівень.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        
        {{-- Пагінація, якщо записів багато --}}
        @if($kndks instanceof \Illuminate\Pagination\LengthAwarePaginator && $kndks->hasPages())
            <div class="card-footer bg-white border-top-0 py-3">
                {{ $items->links() }}
            </div>
        @endif
    </div>
</div>

@endsection