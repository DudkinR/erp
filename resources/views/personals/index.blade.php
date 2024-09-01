@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
            <h1>{{__('Personals')}}</h1>
                <a class="text-right" href="{{ route('personal.create') }}">{{__('Create')}}</a>
            </div>
        </div> 
        <div class="row">
            <div class="col-md-12">
                <input type="text" id="search" class="form-control" placeholder="{{ __('Search') }}">
            </div>
        </div>
        <div class="row">
            <div class="col-md-12" id="show_personals">
            </div>   
        </div>
    </div>
    <script>
        const personals = @json($personals);
        var show_ps = personals;
     //   console.log( show_ps );
        const show_personals = document.getElementById('show_personals');
        function show() {
            const show_personals = document.getElementById('show_personals');
            show_personals.innerHTML = '';

            // Инициализация переменной tableHTML
            let tableHTML = `
                <table class="table">
                    <thead>
                        <tr>
                            <th>{{__('id')}}</th>
                            <th>{{__('tn')}}</th>
                            <th>{{__('FIO')}}</th>
                            <th>{{__('Position')}}</th>
                            <th>{{__('Data')}}</th>
                            @if(Auth::user()->hasRole('quality-engineer','admin'))
                            <th>{{__('Action')}}</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
            `;

            // Заполнение таблицы данными
            show_ps.forEach(personal => {
                tableHTML += `
                    <tr>
                        <td>${personal.id}</td>
                        <td>${personal.tn}</td>
                        <td>${personal.fio}</td>
                        <td>${personal.positions.map (position => position.name).join(', ')}</td>
                        <td>
                            {{__('Phones')}}:<br>
                            ${personal.phones.map(phone => phone.phone).join(', ')}
                            <hr>
                            {{__('Email')}}:<br>
                            ${personal.email}

                        </td>
                        @if(Auth::user()->hasRole('quality-engineer','admin'))
                        <td>
                            <a href="/personal/${personal.id}/edit">Edit</a>
                            <form action="/personal/${personal.id}" method="POST" style="display:inline;">
                                <input type="hidden" name="_token" value="${document.querySelector('meta[name="csrf-token"]').getAttribute('content')}">
                                <input type="hidden" name="_method" value="DELETE">
                                <button type="submit" class="btn btn-danger">Delete</button>
                            </form>
                        </td>
                        @endif
                    </tr>
                `;
            });

            // Закрытие таблицы
            tableHTML += `
                    </tbody>
                </table>
            `;

            // Вставка таблицы в DOM
            show_personals.innerHTML = tableHTML;
        }
        show();
        const search = document.getElementById('search');
        search.addEventListener('keyup', (e) => {
            // пошук по fio та positions 
            const searchString = e.target.value.toLowerCase();
            if (searchString.length > 0) {
                // Виконати запит до сервера
                fetch('/search-personal', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content') // Додати CSRF токен

                    },
                    body: JSON.stringify({ search: searchString })
                })
                .then(response => response.json()) // Обробка відповіді у форматі JSON
                .then(data => {
                   show_ps =data; // Виклик функції для відображення результатів
                   //console.log(show_ps);
                })
                .catch(error => {
                    console.error('Error:', error);
                });
            } else {
                clearResults(); // Очищення результатів, якщо поле порожнє
            }
            show();
        });

        function clearResults() {
            show_ps = personals;
        }

    </script>

@endsection