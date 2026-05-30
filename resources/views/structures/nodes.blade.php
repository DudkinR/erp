<div class="positions-list pl-3">
    @if($structure->positions->count() == 0)
        <small class="text-muted d-block">Посади відсутні</small>
    @else
        @foreach($structure->positions as $position)
            <div class="my-2 p-2 border-left border-info bg-light-custom">
                <div class="d-flex justify-content-between align-items-center">
                    <span class="pos-badge">💼 {{ $position->name }}</span>
                    @if(Auth::user()->hasRole('admin'))
                        <a href="{{ route('positions.edit', $position->id) }}" class="text-secondary small">{{__('[ред]')}}</a>
                    @endif
                </div>

                <div class="personals-list mt-1">
                    @php $personals = $position->personals->where('status', '!=', 'Звільнення'); @endphp
                    
                    @if($personals->count() == 0)
                        <div class="text-danger small">● {{__("free") }}</div>
                    @else
                        @foreach($personals as $personal)
                            <div class="person-badge small">👤 {{ $personal->nickname }}</div>
                        @endforeach
                    @endif
                </div>
            </div>
        @endforeach
    @endif
</div>
