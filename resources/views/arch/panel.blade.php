@extends('layouts.app')

@section('content')
<div class="container py-5">
    <h1 class="mb-4 text-center fw-bold">{{ __('Document Archive Control Panel') }}</h1>

    <div class="row g-4">
        <!-- Створити пакет -->
        <div class="col-md-4">
            <div class="card shadow-sm h-100">
                <div class="card-body text-center">
                    <h5 class="card-title">📦 {{ __('Create Package') }}</h5>
                    <p class="text-muted">{{ __('Add a new document package to the archive') }}</p>
                    <button class="btn btn-primary w-100" data-bs-toggle="modal" data-bs-target="#createPackageModal">
                        {{ __('Create') }}
                    </button>
                </div>
            </div>
        </div>

        <!-- Створити документ -->
        <div class="col-md-4">
            <div class="card shadow-sm h-100">
                <div class="card-body text-center">
                    <h5 class="card-title">📄 {{ __('Create Document') }}</h5>
                    <p class="text-muted">{{ __('Register a new document into the archive') }}</p>
                    <a class="btn btn-success w-100" href="{{ route('archived-documents.create') }}">
                        {{ __('Create') }}
                    </a>
                </div>
            </div>
        </div>

        <!-- Пошук пакета -->
        <div class="col-md-4">
            <div class="card shadow-sm h-100">
                <div class="card-body text-center">
                    <h5 class="card-title">🔍 {{ __('Search Package') }}</h5>
                    <p class="text-muted">{{ __('Find a package by number or name') }}</p>
                    <a class="btn btn-info w-100 text-white" href="{{ route('archived-documents.packages') }}">
                        {{ __('Search') }}
                    </a>
                </div>
            </div>
        </div>

        <!-- Пошук документа -->
        <div class="col-md-4">
            <div class="card shadow-sm h-100">
                <div class="card-body text-center">
                    <h5 class="card-title">🔍 {{ __('Search Document') }}</h5>
                    <p class="text-muted">{{ __('Find a document by type, date or object') }}</p>
                    <a class="btn btn-info w-100 text-white" href="{{ route('archived-documents.index') }}">    
                        {{ __('Search') }}
                    </a>
                </div>
            </div>
        </div>

        <!-- Редагування -->
        <div class="col-md-4">
            <div class="card shadow-sm h-100">
                <div class="card-body text-center">
                    <h5 class="card-title">✏️ {{ __('Edit Documents') }}</h5>
                    <p class="text-muted">{{ __('Update existing archive entries') }}</p>
                    <button class="btn btn-warning w-100" data-bs-toggle="modal" data-bs-target="#editModal">
                        {{ __('Edit') }}
                    </button>
                </div>
            </div>
        </div>
        <!-- дампи -->
        <div class="col-md-4">
            <div class="card shadow-sm h-100">
                <div class="card-body text-center">
                    <h5 class="card-title">📦 {{ __('Dump Documents') }}</h5>
                    <p class="text-muted">{{ __('Create a dump of all documents') }}</p>
                    <a class="btn btn-secondary w-100" href="{{ route('archived-documents.dump.index') }}">
                        {{ __('Dump') }}
                    </a>
                </div>
            </div>
        </div>

    </div>
</div>


{{-- === Modal: Create Package === --}}
<div class="modal fade" id="createPackageModal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
        <form action="{{ route('archived-documents.packages.store') }}" method="post">
          @csrf
          <div class="modal-header bg-primary text-white">
            <h5 class="modal-title">{{ __('Create Package') }}</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
          </div>
          <div class="modal-body">
        <form>
          <div class="mb-3">
            <label class="form-label">{{ __('Package Name') }}</label>
            <input type="text" class="form-control" name="national_name">
          </div>
          <div class="mb-3">
            <label class="form-label">{{ __('Foreign Name') }}</label>
            <input type="text" class="form-control" name="foreign_name">
          </div>  
          <div class="modal-footer">
        <button type="submit" class="btn btn-primary">{{ __('Save') }}</button>
      </div>
        </form>
      
      </div>
      
    </div>
  </div>
</div>

{{-- === Modal: Edit Document/Package === --}}
<div class="modal fade" id="editModal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content p-3">
      <input type="text" name="search" id="search" class="form-control mb-3" placeholder="{{ __('Search by document name or package') }}">
      <div id='link_edit'></div>
    </div>
  </div>
</div>

<script>
const url = '/archived-documents';
const documents = @json($documents);

// слухаємо ввод символів (а не тільки клік)
document.getElementById('search').addEventListener('keyup', function() {
    const searchValue = this.value.toLowerCase().trim();

    if (searchValue.length < 2) { // шукаємо тільки якщо введено мін. 2 символи
        document.getElementById('link_edit').innerHTML = '';
        return;
    }

    let results = [];

    documents.forEach(doc => {
        const matchDoc =
            (doc.code && doc.code.toLowerCase().includes(searchValue)) ||
            (doc.foreign_name && doc.foreign_name.toLowerCase().includes(searchValue)) ||
            (doc.national_name && doc.national_name.toLowerCase().includes(searchValue));

        const matchPackages = (doc.packages || []).some(pkg =>
            (pkg.foreign_name && pkg.foreign_name.toLowerCase().includes(searchValue)) ||
            (pkg.national_name && pkg.national_name.toLowerCase().includes(searchValue))
        );

        if (matchDoc || matchPackages) {
            results.push(doc);
        }
    });

    // беремо тільки перші 10
    results = results.slice(0, 10);

    const linkEdit = document.getElementById('link_edit');
    linkEdit.innerHTML = '';

   if (results.length > 0) {
    results.forEach(doc => {
        const link = document.createElement('a');
        link.href = `${url}/${doc.id}/edit`;

        // Якщо є національне ім’я – беремо його, інакше foreign_name
        const mainName = doc.national_name && doc.national_name.trim() !== '' 
            ? doc.national_name 
            : (doc.foreign_name ?? '');

        // Якщо хочеш показувати обидва, можна друге ім’я окремо
        const secondName = (doc.national_name && doc.foreign_name) 
            ? ` (${doc.foreign_name})` 
            : '';

        // Пакети
        const packages = doc.packages
            .map(pkg => pkg.national_name && pkg.national_name.trim() !== '' 
                ? pkg.national_name 
                : (pkg.foreign_name ?? '')
            )
            .join(', ');

        // Формування тексту
        link.textContent = `${mainName}${secondName} - ${doc.code ?? ''}` 
            + (packages ? ` - ${packages}` : '');

        link.className = 'd-block mb-2';
        linkEdit.appendChild(link);
    });
} else {
    linkEdit.innerHTML = '<p class="text-muted">Нічого не знайдено</p>';
}

});
</script>


@endsection
