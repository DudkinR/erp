@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="row mb-4">
            <div class="col-md-12 d-flex justify-content-between align-items-center flex-wrap gap-2">
                <h1>{{ __('r') }} ({{ __('Risks') }})</h1>
                <a class="btn btn-primary px-4" href="{{ route('r.create') }}"><i class="fa fa-plus"></i> {{ __('Create') }}</a>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <!-- Панель фільтрації та пошуку -->
        <div class="card mb-4 border-0 shadow-sm bg-light">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="input-group">
                            <span class="input-group-text bg-white border-end-0"><i class="fa fa-search text-muted"></i>🔍</span>
                            <input type="text" id="riskSearchInput" class="form-control border-start-0 ps-0" placeholder="{{ __('Пошук за назвою ризику або описом...') }}">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Таблиця ризиків -->
        <div class="row">
            <div class="col-md-12">
                <div class="card border-0 shadow-sm">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0" id="risksTable">
                            <thead class="table-light">
                                <tr>
                                    <th width="60">ID</th>
                                    <th>{{ __('Name') }}</th>
                                    <th>{{ __('Description') }}</th>
                                    <th>{{ __('KNDK Activities') }}</th>
                                    <th width="180" class="text-end">{{ __('Actions') }}</th>
                                </tr>
                            </thead>
                           <tbody>
                                @forelse($risks as $risk)
                                    <tr class="risk-row">
                                        <td class="text-muted">{{ $risk->id }}</td>
                                        <td>
                                            <a href="{{ route('r.show', $risk->id) }}" class="text-decoration-none text-dark fw-bold risk-name">
                                                {{ $risk->name }}
                                            </a>
                                        </td>
                                        <td class="text-muted small risk-desc">{{ Str::limit($risk->description, 120) }}</td>
                                        <td>
                                            @if($risk->kndks->isNotEmpty())
                                                <!-- Кнопка розкриття списку КНДК -->
                                                <button class="btn btn-sm btn-link text-decoration-none p-0 text-start d-flex align-items-center gap-1" 
                                                        type="button" 
                                                        data-bs-toggle="collapse" 
                                                        data-bs-target="#collapseKndk_{{ $risk->id }}" 
                                                        aria-expanded="false" 
                                                        aria-controls="collapseKndk_{{ $risk->id }}">
                                                    📂 <span>{{ __('КНДК') }} ({{ $risk->kndks->count() }})</span> 
                                                    <small class="text-muted" style="font-size: 0.75rem;">▼</small>
                                                </button>

                                                <!-- Прихований блок із бейджами -->
                                                <div class="collapse mt-2" id="collapseKndk_{{ $risk->id }}">
                                                    <div class="d-flex flex-column gap-1 bg-light p-2 rounded border border-light">
                                                        @foreach($risk->kndks as $kndk)
                                                            <span class="badge bg-info bg-opacity-10 text-info border border-info border-opacity-20 text-wrap p-2 text-start rounded-3 fw-normal" style="max-width: 350px; font-size: 0.85rem;">
                                                                📌 {{ $kndk->name }}
                                                            </span>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            @else
                                                <span class="text-muted small">{{ __('Немає прив\'язаних КНДК') }}</span>
                                            @endif
                                        </td>
                                        <td class="text-end">
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('r.show', $risk->id) }}" class="btn btn-sm btn-outline-secondary" title="{{ __('Show') }}">👁️</a>
                                                <a href="{{ route('r.edit', $risk->id) }}" class="btn btn-sm btn-outline-warning" title="{{ __('Edit') }}">✏️</a>
                                                <form action="{{ route('r.destroy', $risk->id) }}" method="POST" onsubmit="return confirm('{{ __('Are you sure?') }}')" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-outline-danger" title="{{ __('Delete') }}">❌</button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr id="noResultsRow">
                                        <td colspan="5" class="text-center text-muted py-4">{{ __('No risks found') }}</td>
                                    </tr>
                                @endforelse
                                <tr id="jsNoResultsRow" style="display: none;">
                                    <td colspan="5" class="text-center text-muted py-4">{{ __('Нічого не знайдено за вашим запитом') }}</td>
                                </tr>
                            </tbody>

                        </table>
                    </div>
                </div>
            </div>
        </div>    
    </div>

    <!-- JS для миттєвого пошуку -->
    <script>
        document.getElementById('riskSearchInput').addEventListener('input', function () {
            const query = this.value.toLowerCase().trim();
            const rows = document.querySelectorAll('.risk-row');
            let hasVisibleRows = false;

            rows.forEach(row => {
                const name = row.querySelector('.risk-name').textContent.toLowerCase();
                const desc = row.querySelector('.risk-desc').textContent.toLowerCase();
                
                if (name.includes(query) || desc.includes(query)) {
                    row.style.display = '';
                    hasVisibleRows = true;
                } else {
                    row.style.display = 'none';
                }
            });

            const jsNoResults = document.getElementById('jsNoResultsRow');
            if (jsNoResults) {
                jsNoResults.style.display = hasVisibleRows ? 'none' : 'table-row';
            }
        });
    </script>
@endsection
