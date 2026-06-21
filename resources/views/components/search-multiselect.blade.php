@props([
    'id',
    'name',
    'label',
    'data',
    'selected' => []
])

<div class="form-group mb-3">
    <label for="{{ $id }}" class="form-label fw-semibold">{{ $label }}</label>
    <input type="text" id="{{ $id }}_search" class="form-control mb-2" placeholder="Пошук...">
    <select id="{{ $id }}" name="{{ $name }}" class="form-select" multiple style="min-height:150px;">
        @foreach($data as $item)
            @php
                $code = $item->full_code ?? '';
            @endphp
            <option value="{{ $item->id }}" 
                @if(in_array($item->id, $selected)) selected @endif>
                {{ $code ? '['.$code.'] ' : '' }}{{ $item->name }}
            </option>
        @endforeach
    </select>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const searchInput = document.getElementById('{{ $id }}_search');
    const selectElement = document.getElementById('{{ $id }}');
    if (!searchInput || !selectElement) return;

    const options = Array.from(selectElement.options);

    searchInput.addEventListener('input', function () {
        const searchTerm = this.value.toLowerCase().trim();

        options.forEach(option => {
            const optionText = option.text.toLowerCase();

            // якщо елемент вибраний — завжди показуємо
            if (option.selected) {
                option.style.display = '';
                return;
            }

            // фільтруємо по тексту
            if (optionText.includes(searchTerm)) {
                option.style.display = '';
            } else {
                option.style.display = 'none';
            }
        });
    });
});
</script>
