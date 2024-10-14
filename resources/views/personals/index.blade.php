@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
            <h1>{{__('Personals')}}</h1>
            @if(Auth::user()->hasRole('quality-engineer','admin'))
                <a class="text-right" href="{{ route('personal.create') }}">{{__('Create')}}</a>
            @endif
            </div>
        </div> 
        <div class="row">
            <div class="col-md-12">
                <div class="input-group">
                    <input type="text" id="search" class="form-control" placeholder="{{ __('Search') }}">
                    <span class="input-group-text" onclick="findResults()">
                        <i class="search_input_button">{{__('Search')}}</i>
                    </span>
                </div>
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
        
        const show_personals = document.getElementById('show_personals');
    
        function show() {
            console.log(show_ps);
            show_personals.innerHTML = '';
    
            // Инициализация переменной tableHTML
            let tableHTML = `
                <table class="table">
                    <thead>
                        <tr>
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
                        <td>${personal.fio}</td>
                        <td>${personal.positions.map(position => position.name).join(', ')}</td>
                        <td>
                            {{__('Phones')}}:<br>
                            ${personal.phones.map(phone => phone.phone).join(', ')}
                            <hr>
                            {{__('Email')}}:<br>
                            ${personal.email}
                            <br>{{__('Division')}}:
                            ${personal.divisions.map(division => division.name)}
                        </td>
                        @if(Auth::user()->hasRole('quality-engineer','admin'))
                        <td>
                            <a href="/personal/${personal.id}/edit" class="btn btn-info w-100" >Edit</a>
                            <form action="/personal/${personal.id}" method="POST" style="display:inline;">
                                <input type="hidden" name="_token" value="${document.querySelector('meta[name="csrf-token"]').getAttribute('content')}">
                                <input type="hidden" name="_method" value="DELETE">
                                <button type="submit" class="btn btn-danger w-100">Delete</button>
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
    
        show(); // Вызов функции отображения таблицы
    
        const search = document.getElementById('search');
        search.addEventListener('keyup', (e) => {
            findResults(e); // Передаем событие в функцию
        });
    
        function clearResults() {
            show_ps = personals;
            show(); // Обновляем таблицу после очистки
        }
    
        function findResults(e) {
            const searchString = e.target.value.toLowerCase();
            if (searchString.length > 0) {
                // Выполняем запрос на сервер
                fetch('/search-personal', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content') // Добавляем CSRF токен
                    },
                    body: JSON.stringify({ search: searchString })
                })
                .then(response => response.json()) // Обрабатываем ответ в формате JSON
                .then(data => {
                    show_ps = data; // Обновляем данные для отображения
                    show(); // Обновляем таблицу после получения данных
                })
                .catch(error => {
                    console.error('Error:', error);
                });
            } else {
                clearResults(); // Очищаем результаты, если поле пустое
            }
        }
    </script>
    

@endsection