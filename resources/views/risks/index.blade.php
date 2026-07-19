@extends('layouts.app')
@section('content')
<div class="container">
    <h1>{{__('Experiences')}}</h1>

    <!-- Панель пошуку та фільтрів -->
    <div class="card p-3 mb-4">
        <div class="row">
            <div class="col-md-4">
                <input type="text" id="searchInput" class="form-control" placeholder="Пошук по тексту...">
            </div>
            <div class="col-md-2">
                <select id="systemFilter" class="form-select">
                    <option value="">{{__('All Systems')}}</option>
                    @foreach(App\Models\System::all() as $system)
                        <option value="{{ $system->name }}">{{ $system->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <select id="equipmentFilter" class="form-select">
                    <option value="">{{__('All Equipments')}}</option>
                    @foreach(App\Models\Type::where('slug','equipment')->first()->children as $equipment)
                        <option value="{{ $equipment->name }}">{{ $equipment->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <select id="actionFilter" class="form-select">
                    <option value="">{{__('All Actions')}}</option>
                    @foreach(App\Models\Type::where('slug','action')->first()->children as $action)
                        <option value="{{ $action->name }}">{{ $action->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <select id="riskFilter" class="form-select">
                    <option value="">{{__('All Risks')}}</option>
                    @foreach(App\Models\Risk::all() as $risk)
                        <option value="{{ $risk->name }}">{{ $risk->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>

    <!-- Список досвіду -->
    <div id="experienceList">
        @foreach($experiences as $experience)
        <div class="experience-item border rounded p-2 mb-2"
             data-text="{{ strtolower($experience->text_uk.' '.$experience->text_en.' '.$experience->text_ru) }}"
             data-system="{{ $experience->systems->pluck('name')->implode(',') }}"
             data-equipment="{{ $experience->equipments->pluck('name')->implode(',') }}"
             data-action="{{ $experience->actions->pluck('name')->implode(',') }}"
             data-risk="{{ $experience->risks->pluck('name')->implode(',') }}">
            <div class="row">
                <div class="col-md-3" @if($experience->text_uk=='') style="background-color:#ffcccc;" @endif>
                    {{$experience->text_uk}}
                </div>
                <div class="col-md-3">{{$experience->text_en}}</div>
                <div class="col-md-3">{{$experience->text_ru}}</div>
                <div class="col-md-3">
                    <a class="btn btn-warning w-100 mb-1" href="{{ route('risks.edit', $experience->id) }}">{{__('Edit')}}</a>
                    <form method="POST" action="{{ route('risks.destroy', $experience->id) }}">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn btn-danger w-100">{{__('Delete')}}</button>
                    </form>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchInput');
    const systemFilter = document.getElementById('systemFilter');
    const equipmentFilter = document.getElementById('equipmentFilter');
    const actionFilter = document.getElementById('actionFilter');
    const riskFilter = document.getElementById('riskFilter');
    const items = document.querySelectorAll('.experience-item');

    function filterItems() {
        const query = searchInput.value.toLowerCase();
        const system = systemFilter.value.toLowerCase();
        const equipment = equipmentFilter.value.toLowerCase();
        const action = actionFilter.value.toLowerCase();
        const risk = riskFilter.value.toLowerCase();

        items.forEach(item => {
            const text = item.dataset.text;
            const sys = item.dataset.system.toLowerCase();
            const eq = item.dataset.equipment.toLowerCase();
            const act = item.dataset.action.toLowerCase();
            const rk = item.dataset.risk.toLowerCase();

            const matchSearch = text.includes(query);
            const matchSystem = !system || sys.includes(system);
            const matchEquipment = !equipment || eq.includes(equipment);
            const matchAction = !action || act.includes(action);
            const matchRisk = !risk || rk.includes(risk);

            if (matchSearch && matchSystem && matchEquipment && matchAction && matchRisk) {
                item.style.display = '';
                // підсвічування знайденого слова
                if(query){
                    item.innerHTML = item.innerHTML.replace(new RegExp(query, 'gi'), 
                        match => `<span style="background:yellow;">${match}</span>`);
                }
            } else {
                item.style.display = 'none';
            }
        });
    }

    [searchInput, systemFilter, equipmentFilter, actionFilter, riskFilter].forEach(el => {
        el.addEventListener('input', filterItems);
        el.addEventListener('change', filterItems);
    });
});
</script>
@endsection
