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
    @if(Auth::check() && Auth::user()->hasRole('quality-engineer','admin','user'))
        <div class="row">
            <div class="col-md-12">
            <h1>{{__('Dictionary')}}</h1>
                <a class="btn btn-light w-100" href="{{ route('dictionary.create') }}">{{__('Create')}}</a>
            </div>
        </div>      
    @endif
    <div class="row">
        <div class="col-md-12">
            <div class="input-group">
                <input type="text" id="search" class="form-control" placeholder="{{ __('Search') }}"
                @if(isset($_GET['word']))
                    value = "{{$_GET['word']}}"
                @endif
                >
                <span class="input-group-text" onclick="findResults()">
                    <i class="search_input_button">{{__('Search')}}</i>
                </span>
            </div>
        </div>
    </div>
    <div class="row" id="dict">       
    </div>
</div>
<script>
    const dictionary = @json($dictionary);
    var show_dict = dictionary;
    const dict = document.getElementById('dict');
    function show() {
        dict.innerHTML = '';
        // Инициализация переменной tableHTML
        let tableHTML = `
            <table class="table">
                <thead>
                    <tr>
                        <th>{{__('ru')}}</th>
                        <th>{{__('uk')}}</th>
                        <th>{{__('en')}}</th>
                        <th>{{__('description')}}</th>
                        <th>{{__('example')}}</th>
                    </tr>
                </thead>
                <tbody>
        `;
        // Заполнение таблицы данными
        
        show_dict.forEach(dict => {
            tableHTML += `
                <tr>
                    <td>${dict.ru}</td>
                    <td>
                    ${dict.uk}
                    </td>
                    <td>${dict.en}</td>
                    <td style="background-color: yellow
                    ">${dict.description}</td>
                    <td>${dict.example}
                    <hr>
                     @if(Auth::user()->hasRole('quality-engineer','admin'))
                        <a href="/dictionary/${dict.id}/show">Show</a> 
                        <a href="/dictionary/${dict.id}/edit">Edit</a> 
                        <form action="/dictionary/${dict.id}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit">Delete</button>
                        </form>
                     @endif
                    </td>
                </tr>
            `;
        });  
        // Закрытие таблицы
        tableHTML += `
            </tbody>
        </table>
        `;

        // Добавление таблицы на страницу
        dict.innerHTML = tableHTML;
    }
     // search 
// Функция для задержки выполнения (debounce)
function debounce(func, wait) {
    let timeout;
    return function(...args) {
        clearTimeout(timeout);
        timeout = setTimeout(() => func.apply(this, args), wait);
    };
}

// Функция для поиска
const search = document.getElementById('search');
const performSearch = () => {
    const searchValue = search.value.toLowerCase();
    show_dict = dictionary.filter(dict => 
        (dict.ru && dict.ru.toLowerCase().includes(searchValue)) || 
        (dict.uk && dict.uk.toLowerCase().includes(searchValue)) || 
        (dict.en && dict.en.toLowerCase().includes(searchValue)) || 
        (dict.description && dict.description.toLowerCase().includes(searchValue)) || 
        (dict.example && dict.example.toLowerCase().includes(searchValue))
    );
    show();
};

// Применяем debounce к функции поиска
search.addEventListener('input', debounce(performSearch, 300)); // Задержка 300 мс



    show();
</script>
@endsection