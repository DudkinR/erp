@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">{{ __('Create Document') }}</h1>

    <div class="mb-3">
        <a href="{{ route('archived-documents.import') }}" class="btn btn-secondary">
            <i class="bi bi-upload"></i> {{ __('Import Documents') }}
        </a>
    </div>

    <form method="POST" action="{{ route('archived-documents.store') }}" enctype="multipart/form-data">
        @csrf

        {{-- === Пакет === --}}
        <div class="card mb-4">
            <div class="card-header fw-bold">
                {{ __('Package') }}
            </div>
            <div class="card-body">
                @if(count($packages) === 0)
                    <div class="alert alert-info">{{ __('You can create a new document or select an existing package.') }}</div>
                @else
                    <div class="mb-3">
                        <label for="package_id">{{ __('Select Package') }}</label>
                        <select class="form-control" id="package_id" name="package_id" size="5" style="display:none;"></select>
                        <input type="text" id="package_search" class="form-control mt-2" style="display:none;" placeholder="{{ __('Search packages') }}" onkeyup="filterPackages()">
                    </div>
                @endif

                <div class="form-check mb-3">
                    <input type="checkbox" class="form-check-input" id="new_package_checkbox" name="new_package_checkbox" checked>
                    <label class="form-check-label" for="new_package_checkbox">{{ __('Create New Package') }}</label>
                </div>

                <div id="new_package_fields">
                    <input type="text" class="form-control mb-2" name="new_package_national_name" placeholder="{{ __('National Name package') }}">
                    <input type="text" class="form-control" name="new_package_foreign_name" placeholder="{{ __('Foreign Name package') }}">
                </div>
            </div>
        </div>

        {{-- === Документ === --}}
        <div class="card mb-4">
            <div class="card-header fw-bold">
                {{ __('Document Information') }}
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <label>{{ __('Foreign Name') }}</label>
                    <input type="text" class="form-control" name="foreign_name">
                </div>
                <div class="mb-3">
                    <label>{{ __('National Name') }}</label>
                    <input type="text" class="form-control" name="national_name" required>
                </div>

                {{-- Дати --}}
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label>{{ __('Registration Date') }}</label>
                        <input type="date" class="form-control" name="reg_date">
                    </div>
                    <div class="col-md-6">
                        <label>{{ __('Production Date') }}</label>
                        <input type="date" class="form-control" name="production_date">
                    </div>
                </div>

                {{-- Компактні поля --}}
                <div class="row g-2">
                    <div class="col-md-4">
                        <input type="text" class="form-control" name="kor" placeholder="{{ __('Correspondent') }}">
                    </div>
                    <div class="col-md-4">
                        <input type="text" class="form-control" name="part" placeholder="{{ __('Project Part') }}">
                    </div>
                    <div class="col-md-4">
                        <input type="text" class="form-control" name="contract" placeholder="{{ __('Contract Number') }}">
                    </div>
                </div>

                <div class="row g-2 mt-2">
                    <div class="col-md-4">
                        <input type="text" class="form-control" name="develop" placeholder="{{ __('Developer') }}">
                    </div>
                    <div class="col-md-4">
                        <input type="text" class="form-control" name="object" placeholder="{{ __('Object') }}">
                    </div>
                    <div class="col-md-4">
                        <input type="text" class="form-control" name="unit" placeholder="{{ __('Unit') }}">
                    </div>
                </div>

                <div class="row g-2 mt-2">
                    <div class="col-md-4">
                        <input type="text" class="form-control" name="stage" placeholder="{{ __('Project Stage') }}">
                    </div>
                    <div class="col-md-4">
                        <input type="text" class="form-control" name="code" placeholder="{{ __('Document Code') }}">
                    </div>
                    <div class="col-md-4">
                        <input type="text" class="form-control" name="inventory" placeholder="{{ __('Inventory Number') }}">
                    </div>
                </div>
            </div>
        </div>

        {{-- === Місце зберігання === --}}
        <div class="card mb-4">
            <div class="card-header fw-bold">
                {{ __('Storage Location') }}
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <label>{{ __('File') }}</label>
                    <input type="file" class="form-control" name="scan" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png" required>
                </div>

                <div class="mb-3">
                    <div class="form-check form-check-inline">
                        <input type="radio" class="form-check-input" id="storage_location_local" name="storage_location" value="tech-archive" checked>
                        <label class="form-check-label" for="storage_location_local">{{ __('Tech-archive') }}</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input type="radio" class="form-check-input" id="storage_location_external" name="storage_location" value="external-archive">
                        <label class="form-check-label" for="storage_location_external">{{ __('Common archive') }}</label>
                    </div>
                </div>

                <div class="row g-2">
                    <div class="col">
                        <input type="number" class="form-control" id="storage_location_row" placeholder="{{ __('Row') }}">
                    </div>
                    <div class="col">
                        <input type="number" class="form-control" id="storage_location_floor" placeholder="{{ __('Floor') }}">
                    </div>
                    <div class="col">
                        <input type="number" class="form-control" id="storage_location_shelf" placeholder="{{ __('Shelf') }}">
                    </div>
                    <div class="col">
                        <input type="number" class="form-control" id="storage_location_box" placeholder="{{ __('Box') }}">
                    </div>
                    <div class="col">
                        <input type="number" class="form-control" id="storage_location_folder" placeholder="{{ __('Folder') }}">
                    </div>
                    <div class="col">
                        <input type="number" class="form-control" id="storage_location_file" placeholder="{{ __('File') }}">
                    </div>
                </div>

                <input type="text" class="form-control mt-2" id="storage_location" name="storage_location" >
            </div>
        </div>
        

        <div class="text-end">
            <button type="submit" class="btn btn-primary btn-lg">
                <i class="bi bi-save"></i> {{ __('Create') }}
            </button>
        </div>
    </form>
</div>
@endsection
