@extends('layouts.app')

@section('content')
<div class="container">
           @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        @if(session('success'))
    <div class="alert alert-success">{{ __(session('success')) }}</div>
@endif
@if(session('error'))
    <div class="alert alert-danger">{{ __(session('error')) }}</div>
@endif
    <div class="row mb-3">
        <div class="col-md-12 d-flex justify-content-between align-items-center">
            <h1>{{ __('Division') }}: {{ $division->abv }}</h1>
            <a class="btn btn-secondary" href="{{ route('divisions.index') }}">{{ __('Back to Divisions') }}</a>
        </div>
    </div>
    
    <div class="row">
        <div class="col-md-12">
            <ul>
                <li>
                    <strong>{{ $division->name }}</strong>

                    {{-- Посади у цьому підрозділі --}}
                    <ul>
                        @foreach($division->positions as $position)
                            <li>
                                {{ $position->name }}
                                <ul>
                                    @foreach($position->personals as $person)
                                        <li>{{ $person->fio }}</li>
                                    @endforeach
                                </ul>
                            </li>
                        @endforeach
                    </ul>

                    {{-- Поточні співробітники напряму у підрозділі --}}
                    <ul>
                        @foreach($division->currentPersonals as $person)
                            <li>{{ $person->fio }} (безпосередньо у підрозділі)</li>
                        @endforeach
                    </ul>

                    {{-- Діти --}}
                    @foreach($division->children as $child)
                        @include('divisions.tree', ['division' => $child])
                    @endforeach
                </li>
            </ul>

        </div>
    </div>
</div>
<!-- Додати підрозділ -->
<div class="modal fade" id="addDivisionModal" tabindex="-1">
  <div class="modal-dialog">
    <form id="addDivisionForm">
      @csrf
      <input type="hidden" name="parent_id" id="addDivisionParent">
      <div class="modal-content">
        <div class="modal-header"><h5>Новий підрозділ</h5></div>
        <div class="modal-body">
          <input type="text" name="name" class="form-control" placeholder="Назва">
          <input type="text" name="abv" class="form-control mt-2" placeholder="Абревіатура">
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-success">Зберегти</button>
        </div>
      </div>
    </form>
  </div>
</div>

<!-- Редагувати підрозділ -->
<div class="modal fade" id="editDivisionModal" tabindex="-1">
  <div class="modal-dialog">
    <form id="editDivisionForm">
      @csrf
      <input type="hidden" name="id" id="editDivisionId">
      <div class="modal-content">
        <div class="modal-header"><h5>Редагувати підрозділ</h5></div>
        <div class="modal-body">
          <input type="text" name="name" class="form-control" placeholder="Назва">
          <input type="text" name="abv" class="form-control mt-2" placeholder="Абревіатура">
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-primary">Оновити</button>
        </div>
      </div>
    </form>
  </div>
</div>

<script>
 // Додавання підрозділу
$('#addDivisionModal').on('show.bs.modal', function (event) {
    let button = $(event.relatedTarget);
    let parentId = button.data('parent');
    $('#addDivisionParent').val(parentId);
});

$('#addDivisionForm').on('submit', function(e){
    e.preventDefault();
    $.ajax({
        url: '/divisions/store',
        method: 'POST',
        data: $(this).serialize(),
        success: function(resp){
            location.reload();
        },
        error: function(err){
            alert('Помилка при додаванні');
        }
    });
});

// Редагування підрозділу
$('#editDivisionModal').on('show.bs.modal', function (event) {
    let button = $(event.relatedTarget);
    let id = button.data('id');
    $('#editDivisionId').val(id);
    // можна ще зробити AJAX для підвантаження даних
});

$('#editDivisionForm').on('submit', function(e){
    e.preventDefault();
    $.ajax({
        url: '/divisions/update',
        method: 'POST',
        data: $(this).serialize(),
        success: function(resp){
            location.reload();
        },
        error: function(err){
            alert('Помилка при редагуванні');
        }
    });
});

// Видалення
function deleteDivision(id){
    if(confirm('Видалити підрозділ?')){
        $.ajax({
            url: '/divisions/'+id,
            method: 'DELETE',
            data: {_token: '{{ csrf_token() }}'},
            success: function(){
                location.reload();
            },
            error: function(){
                alert('Помилка при видаленні');
            }
        });
    }
}


</script>
@endsection
