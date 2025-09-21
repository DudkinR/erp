@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h1>{{__('Edit Personal')}}</h1>
                <a href="{{ route('personal.index') }}" class="btn btn-secondary">{{__('Back')}}</a>
                <form method="POST" action="{{ route('personal.update',$personal) }}">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <input type="hidden" name="_method" value="PUT">
                    <div class="form-group">
                        <label for="tn">{{__('tn')}}</label>
                        <input type="text" class="form-control" id="tn" name="tn" value="{{ $personal->tn }}">
                    </div>
                    <div class="form-group">
                        <label for="fio">{{__('FIO')}}</label>
                        <input type="text" class="form-control" id="fio" name="fio" value="{{ $personal->fio }}">
                    </div>
                    <div class="form-group">
                        <label for="email">{{__('Email')}}</label>
                        <input type="email" class="form-control" id="email" name="email" value="{{ $personal->email }}">
                    </div>
                    <div class="form-group">
                        <label for="phone">{{__('Phone')}}</label>
                        <input type="phone" class="form-control" id="phone" name="phone" value="{{ $personal->phone }}">
                    </div>
                    <div class="form-group">
                        <label for="boss">{{__('Boss')}}</label>
                        <select class="form-control" id="boss" name="boss">
                            <option value=""></option>
                            @foreach($users as $u)
                                <option value="{{ $u->id }}" @if($boss->pluck('id')->contains($u->id)) selected @endif>{{ $u->name }}</option>                                
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="relatedUsers">{{__('Related Users')}}</label>
                        <div id="checkboxList" class="checkbox-list" 
                            style="max-height: 300px; overflow-y: auto; border: 1px solid #ddd; padding: 10px;">
                            @foreach($relatedUsers as $ru)
                                <div class="form-check" id="checkbox-{{ $ru->id }}">
                                    <input class="form-check-input" type="checkbox" value="{{ $ru->id }}" 
                                        id="relatedUser{{ $ru->id }}" 
                                        name="relatedUsers[]" checked>
                                    <label class="form-check-label" for="relatedUser{{ $ru->id }}">
                                        {{ $ru->name }}
                                        ({{ $ru->tn ?? '' }})
                                    </label>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="form-group">
                        <input type="text" id="removeRelatedUserInput" 
                            placeholder="{{__('select name or tn')}}" 
                            class="form-control mb-2">

                        <select class="form-control" id="relatedUsersSelect">
                            <option value=""></option>
                            @foreach($users as $u)
                                @if(!$relatedUsers->pluck('id')->contains($u->id))
                                    <option value="{{ $u->id }}">{{ $u->name }} ({{ $u->tn }})</option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="position">{{__('Position')}}</label>
                        <input type="text" id="removePositionInput" 
                            placeholder="{{__('select position')}}" 
                            class="form-control mb-2"> 
                        <select class="form-control" id="position" name="position">
                            @foreach($positions as $position)
                                <option value="{{ $position->id }}" @if($personal->positions->contains($position)) selected @endif>{{ $position->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="status">{{__('Status')}}</label>
                        <?php $statuses = [ 'На роботі', 'Відпустка','Звілнений', 'Лікарняний']; ?>
                        <select class="form-control" id="status" name="status">
                            @foreach($statuses as $status)
                                <option value="{{ $status }}" @if($personal->status == $status) selected @endif>{{ $status }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="date_start">{{__('Date start')}}</label>
                        <input type="date" class="form-control" id="date_start" name="date_start" value="{{ $personal->date_start }}">
                    </div>
                    <div class="form-group">
                        <label for="date_end">{{__('Date end')}}</label>
                        <input type="date" class="form-control" id="date_end" name="date_end" value="{{ $personal->date_end }}">
                    </div>
                    <div class="form-group">
                        <label for="comment">{{__('Comment')}}</label>
                        <textarea class="form-control" id="comment" name="comment">{{ $personal->comment }}</textarea>
                    
                    @if($personal->comments->count() > 0)
                             <h3>{{__('Comments')}}</h3>
                                @foreach($personal->comments as $comment)
                                    <p>
                                        <b>{{ $comment->comment }}</b>
                                        <u>{{ $comment->created_at }}</u>
                                    </p>
                                @endforeach
                    @endif
                    </div>    
                    <div class="form-group">
                        <?php $roles = App\Models\Role::orderBy('id', 'desc')->get(); ?>
                        <label for="roles">{{__('Roles')}}</label>
                        <select class="form-control" id="roles" name="roles[]" multiple>
                            @foreach($roles as $role)
                                <option value="{{ $role->id }}" @if($personal->roles->contains($role)) selected @endif>{{ $role->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="division">{{__('Division')}}</label>
                        <input type="text" id="removeDivisionInput" 
                            placeholder="{{__('select division')}}" 
                            class="form-control mb-2">
                        <select class="form-control" id="division_id" name="division_id">
                            <option value="0"></option>
                            @foreach($divisions as $division)
                                <option value="{{ $division->id }}" 
                                @if($personal->divisions->pluck('id')->contains($division->id)) selected @endif
                                    >{{ $division->name }}</option>
                            @endforeach
                        </select>
                    </div>                    
                    <button type="submit" class="btn btn-primary w-100">{{__('Save')}}</button>
                </form>
            </div>
        </div>
    </div>
  <script>
    const users = @json($users);
// 🔎 Фільтрація select при наборі
    const searchInput = document.getElementById('removeRelatedUserInput');

    const select = document.getElementById('relatedUsersSelect');
    
    searchInput.addEventListener('keyup', function () {
        let term = this.value.toLowerCase();
        for (let option of select.options) {
            if (!option.value) continue;
            option.style.display = option.text.toLowerCase().includes(term) ? '' : 'none';
        }
    });

    // ➕ Додавання користувача в checkbox-list при виборі
    select.addEventListener('change', function () {
        let userId = this.value;
        if (!userId) return;

        let user = users.find(u => u.id == userId);
        if (!user) return;

        // Створюємо елемент-чекбокс
        let checkboxList = document.getElementById('checkboxList');
        let div = document.createElement('div');
        div.classList.add('form-check');
        div.id = `checkbox-${user.id}`;
        div.innerHTML = `
            <input class="form-check-input" type="checkbox" value="${user.id}" 
                   id="relatedUser${user.id}" name="relatedUsers[]" checked>
            <label class="form-check-label" for="relatedUser${user.id}">
                ${user.name} (${user.tn ?? ''})
            </label>
        `;
        checkboxList.appendChild(div);

        // Прибираємо зі select
        this.querySelector(`option[value="${userId}"]`).remove();

        // Скидаємо select і пошук
        this.value = "";
        searchInput.value = "";
        for (let option of this.options) option.style.display = '';
    });

    //removePositionInput
    const positions = @json($positions);
    const positionInput = document.getElementById('removePositionInput');
    const positionSelect = document.getElementById('position');
    positionInput.addEventListener('keyup', function () {
        let term = this.value.toLowerCase();
        for (let option of positionSelect.options) {
            if (!option.value) continue;
            option.style.display = option.text.toLowerCase().includes(term) ? '' : 'none';
        }
    });
    //removeDivisionInput
    const divisions = @json($divisions);
    const divisionInput = document.getElementById('removeDivisionInput');
    const divisionSelect = document.getElementById('division_id');
    divisionInput.addEventListener('keyup', function () {
        let term = this.value.toLowerCase();
        for (let option of divisionSelect.options) {
            if (!option.value) continue;
            option.style.display = option.text.toLowerCase().includes(term) ? '' : 'none';
        }
    });

  </script>
@endsection