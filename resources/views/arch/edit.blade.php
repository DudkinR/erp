@extends('layouts.app')

@section('content')
<div class="container py-4">

    <form action="{{ route('archived-documents.update', $document->id) }}" method="POST" enctype="multipart/form-data" class="card shadow-lg border-0 rounded-4">
        @csrf
        @method('PUT')

        <div class="card-header bg-primary text-white rounded-top-4">
            <h4 class="mb-0">✏️ Редагувати документ</h4>
        </div>

       <div class="card-body">

          {{-- === Пакет === --}}
            <h5 class="mb-3">📦 Пакет</h5>

            <div class="mb-3">
            
                <input type="hidden" name="package_foreign_name" id="package_foreign_name" value="">
                <input type="hidden" name="package_national_name" id="package_national_name" value="">
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#packageModal">
                    🔍 Вибрати пакет
                </button>
                    <span id="selected_package" class="text-muted">
                        {{ $document->packages->first()
                            ? ($document->packages->first()->national_name ?: $document->packages->first()->foreign_name)
                            : 'Не вибрано' }}
                    </span>

                <input type="hidden" name="package_id" id="package_id" value="{{ $package->id}}">

            </div>
          {{-- === Modal === --}}
            <div class="modal fade" id="packageModal" tabindex="-1">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Вибір або створення пакета</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <ul class="nav nav-tabs mb-3" id="packageTabs">
                                <li class="nav-item">
                                    <a class="nav-link active" data-bs-toggle="tab" href="#searchTab">Пошук</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" data-bs-toggle="tab" href="#createTab">Створити</a>
                                </li>
                            </ul>
                            <div class="tab-content">
                                <div class="tab-pane fade show active" id="searchTab">
                                    <input type="text" id="package_search" class="form-control mb-3" placeholder="Введіть назву пакета...">
                                    <div id="package_results"></div>
                                </div>
                                <div class="tab-pane fade" id="createTab">
                                    <div class="mb-3">
                                        <input type="text" id="create_package_national" class="form-control mb-2" placeholder="Українська назва пакета">
                                        <input type="text" id="create_package_foreign" class="form-control" placeholder="Іноземна назва пакета">
                                    </div>
                                    <button type="button" class="btn btn-success" onclick="createPackage()">Створити</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <hr>
           

            {{-- === Документ === --}}
            <h5 class="mb-3">📄 Картка документа</h5>
            <div class="row g-3">
                <div class="col-md-4 position-relative">
                    <label class="form-label">Назва документа <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" name="national_name" required id="national_name" autocomplete="off" onfocus="initDocAutocomplete()" value="{{ $document->national_name }}">
                </div>

                <div class="col-md-4 position-relative">
                    <label class="form-label">Іноземна назва</label>
                    <input type="text" class="form-control" name="foreign_name" id="foreign_name" autocomplete="off" onfocus="initDocAutocomplete()" value="{{ $document->foreign_name }}">
                    <div id="doc_suggestions" class="list-group position-absolute w-100" style="z-index: 2002;"></div>
                </div>
                <div class="col-md-4 position-relative">
                    <label class="form-label">Архівний №</label>
                    <input type="text" class="form-control" name="inventory" placeholder="{{ __('Inventory No.') }}" value="{{ $document->inventory }}">
                </div>

                <<div class="col-md-4">
                    <label class="form-label">Дата реєстрації</label>
                    <input type="date" class="form-control" name="reg_date" value="{{ $document->reg_date }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Дата  в виробництві</label>
                    <input type="date" class="form-control" name="production_date" value="{{ $document->production_date }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Службова записка</label>
                    <input type="text" class="form-control" name="notes" placeholder="{{ __('Службова записка') }}" value="{{ $document->notes }}">
                </div>
            </div>

            <div class="row g-3 mt-2">
                <div class="col-md-4"><input type="text" class="form-control" name="kor" placeholder="{{ __('Contractor') }}" value="{{ $document->kor }}"></div>
                <div class="col-md-4"><input type="text" class="form-control" name="part" placeholder="{{ __('Part') }}" value="{{ $document->part }}"></div>
                <div class="col-md-4"><input type="text" class="form-control" name="contract" placeholder="{{ __('Contract') }}" value="{{ $document->contract }}"></div>
            </div>

            <div class="row g-3 mt-2">
                <div class="col-md-4">
                    <input type="text" class="form-control" name="develop" placeholder="{{ __('Developer') }}" id="develop_input" autocomplete="off" onfocus="initDevelopAutocomplete()" value="{{ $document->develop }}">
                    <div id="develop_suggestions" class="list-group position-absolute w-100" style="z-index: 2000;"></div>
                </div>
                <div class="col-md-4">
                    <input type="text" class="form-control" name="object" placeholder="{{ __('Object') }}" id="object_input" autocomplete="off" onfocus="initObjectAutocomplete()" value="{{ $document->object }}">
                    <div id="object_suggestions" class="list-group position-absolute w-100" style="z-index: 2001;"></div>
                </div>
                <div class="col-md-4"><input type="text" class="form-control" name="unit" placeholder="{{ __('Unit') }}" value="{{ $document->unit }}"></div>
            </div>

            <div class="row g-3 mt-2">
                <div class="col-md-3">
                    <label class="form-label">Стадія</label>
                    <input type="text" class="form-control" name="stage" placeholder="{{ __('Stage') }}" value="{{ $document->stage }}"></div>
                <div class="col-md-3">
                    <label class="form-label">Шифр</label>
                    <input type="text" class="form-control" name="code" placeholder="{{ __('Code') }}" value="{{ $document->code }}"></div>
                <div class="col-md-3">
                    <label class="form-label">Кількість сторінок</label>
                    <input type="number" class="form-control" name="pages" placeholder="{{ __('Pages') }}" value="{{ $document->pages }}"></div>
               <div class="col-md-3">
                <label class="form-label">Статус</label>
                <select class="form-select" name="status" id="status_select">
                    <option @if($document->status == 'active') selected @endif value="active">{{ __('Active') }}</option>
                    <option @if($document->status == 'canceled') selected @endif value="canceled">{{ __('Canceled') }}</option>
                    <option @if($document->status == 'replaced') selected @endif value="replaced">{{ __('Replaced') }}</option>
                    <option @if($document->status == 'draft') selected @endif value="draft">{{ __('Draft') }}</option>
                    <option @if($document->status == 'other') selected @endif value="other">{{ __('Other') }}</option>
                </select>

                <!-- приховане поле для ID -->
                <input type="hidden" name="replaced_id" id="replaced_id" @if($document->status == 'replaced') value="{{ $document->replaced_id }}" @else value="" @endif>

                <!-- пошук документа -->
                <div id="replaced_search_block" style="display:none; margin-top:10px;">
                    <label class="form-label">Виберіть документ для заміни</label>
                    <input type="text" class="form-control" id="doc_search" placeholder="Введіть назву чи шифр...">
                    <div id="doc_results" style="border:1px solid #ccc; max-height:150px; overflow-y:auto;"></div>
                </div>

                <!-- показ вибраного -->
                <label class="form-label" id="replaced_label" style="margin-top:10px;">
                    @if($document->status == 'replaced')
                        Вибрано документ для заміни: {{ $document->replaced_document_name }}
                    @endif
                </label>
            </div>


            </div>

            <hr>

            {{-- === Зберігання === --}}
            <h5 class="mb-3">📂 Місце зберігання</h5>
            <div class="mb-3">
                <input type="file" class="form-control" name="scan" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png" >
                <a href="{{ asset($document->scan) }}" target="_blank">Переглянути файл</a>
            </div>

            <div class="form-check form-check-inline">
                <input type="radio" class="form-check-input" name="storage_location" value="tech-archive" checked>
                <label class="form-check-label">Технічний архів</label>
            </div>
            <div class="form-check form-check-inline">
                <input type="radio" class="form-check-input" name="storage_location" value="external-archive">
                <label class="form-check-label">Загальний архів</label>
            </div>

            <input type="text" class="form-control mt-2" name="location" placeholder="Деталі (ряд, шафа, коробка...)" value="{{ $document->storage_location }}">

        </div>

        <div class="card-footer text-end bg-light rounded-bottom-4">
            <a href="{{ route('archived-documents.index') }}" class="btn btn-secondary me-2">⬅ Назад</a>
            <button type="submit" class="btn btn-success">💾 Зберегти</button>
        </div>
    </form>

</div>

@include('arch.js')
@endsection
