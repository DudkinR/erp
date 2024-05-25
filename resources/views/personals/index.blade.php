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
        console.log( show_ps );
        const show_personals = document.getElementById('show_personals');
        function show() {
            const show_personals = document.getElementById('show_personals');
            show_personals.innerHTML = '';

            // Инициализация переменной tableHTML
            let tableHTML = `
                <table class="table">
                    <thead>
                        <tr>
                            <th>tn</th>
                            <th>FIO</th>
                            <th>Position</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
            `;

            // Заполнение таблицы данными
            show_ps.forEach(personal => {
                tableHTML += `
                    <tr>
                        <td>${personal.tn}</td>
                        <td>${personal.fio}</td>
                        <td>${personal.positions.map (position => position.name).join(', ')}</td>
                        <td>${personal.status}</td>
                        <td>
                            <a href="/personals/${personal.id}/edit">Edit</a>
                            <form action="/personals/${personal.id}" method="POST" style="display:inline;">
                                <input type="hidden" name="_token" value="${document.querySelector('meta[name="csrf-token"]').getAttribute('content')}">
                                <input type="hidden" name="_method" value="DELETE">
                                <button type="submit" class="btn btn-danger">Delete</button>
                            </form>
                        </td>
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
            const filtered_ps = personals.filter(personal => {
                return personal.fio.toLowerCase().includes(searchString) || personal.positions.map(position => position.name).join(', ').toLowerCase().includes(searchString);
            });
            show_ps = filtered_ps;
            show();
        });

    </script>

@endsection