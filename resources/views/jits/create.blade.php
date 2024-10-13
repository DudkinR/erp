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
        <div class="row">
            <div class="col-md-12">
                <h1>{{__('JIT')}}</h1>
                <form method="POST" action="{{ route('jits.store') }}">
                    <div class="row">
                  
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="name_uk">{{__('Name UK')}}</label>
                                <input type="text" class="form-control" id="name_uk" name="name_uk" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="name_en">{{__('Name EN')}}</label>
                                <input type="text" class="form-control" id="name_en" name="name_en">
                            </div>
                        </div>      
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="name_ru">{{__('Name RU')}}</label>
                                <input type="text" class="form-control" id="name_ru" name="name_ru" >
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="description_uk">{{__('Description UK')}}</label>
                                <textarea class="form-control" id="description_uk" name="description_uk" rows="3" required></textarea>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="description_en">{{__('Description EN')}}</label>
                                <textarea class="form-control" id="description_en" name="description_en" rows="3"></textarea>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="description_ru">{{__('Description RU')}}</label>
                                <textarea class="form-control" id="description_ru" name="description_ru" rows="3"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="keywords">{{__('Keywords')}}</label>
                                <textarea class="form-control" id="keywords" name="keywords" rows="3"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="number">{{__('Number')}}</label>
                                <input type="text" class="form-control" id="number" name="number">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="new_jitqw">{{__('New Question')}}</label>
                                <input type="text" class="form-control" id="new_jitqw" name="new_jitqw" onkeyup="find_jitqws()">
                                <button type="button" class="btn btn-warning w-100" onclick="create_new_jitqw(new_jitqw.value)">{{__('Create')}}</button>
                            </div>
                        </div>
                    </div>
                    <div class="container" id="select_jitqws">
                    </div>
                    <div class="container" id="jitqws">
                    </div>

                    </div>
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <button type="submit" class="btn btn-primary w-100">{{__('Create')}}</button>
                </form>
            </div>
        </div>
    </div>
    <script>
        var jitqws = @json($jitqws);
       
        const new_jitqw = document.getElementById('new_jitqw');
        const select_jitqws = document.getElementById('select_jitqws');
        const jitqws_div = document.getElementById('jitqws');
    
        // Поиск вопросов на основе введенного текста
        function find_jitqws() {
            const value = new_jitqw.value.trim().toLowerCase();
            if (!value) {
                select_jitqws.innerHTML = '';
                return;
            }
    
            const words = value.split(' ');
    
            const filtered_jitqws = jitqws.map(jitqw => {
                const matchCount = words.reduce((count, word) => {
                    if (jitqw.description_ru.toLowerCase().includes(word) || 
                        jitqw.description_uk.toLowerCase().includes(word) || 
                        jitqw.description_en.toLowerCase().includes(word)) {
                        return count + 1;
                    }
                    return count;
                }, 0);
                return { ...jitqw, matchCount };
            })
            .filter(jitqw => jitqw.matchCount > 0)
            .sort((a, b) => b.matchCount - a.matchCount)
            .slice(0, 3);
    
            show_jitqws(filtered_jitqws);
    
           
        }
    
        // Отображение найденных вопросов
        function show_jitqws(filtered_jitqws) {
            select_jitqws.innerHTML = '';
            filtered_jitqws.forEach(jitqw => {
                const div = document.createElement('div');
                div.innerHTML = `
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>${jitqw.description_ru}</label>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>${jitqw.description_uk}</label>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>${jitqw.description_en}</label>
                                <button class="btn btn-sm btn-primary" onclick="add_jitqw(${jitqw.id}, '${jitqw.description_ru}', '${jitqw.description_uk}', '${jitqw.description_en}')">Добавить</button>
                            </div>
                        </div>
                    </div>
                `;
                select_jitqws.appendChild(div);
            });
        }
    
        // Добавление выбранного вопроса в контейнер
        function add_jitqw(id, description_ru, description_uk, description_en) {
            const div = document.createElement('div');
            div.innerHTML = `
                <input type="hidden" name="jitqws[]" value="${id}">
                <div class="row">
                    <div class="col-md-4">
                        <label> {{__('UK')}}</label>
                        <textarea class="form-control" rows="3" name="description_uk[{$id}]">${description_uk.trim()}</textarea>
                        </div>
                    <div class="col-md-4">
                        <label>{{__('EN')}}</label>
                        <textarea class="form-control" rows="3" name="description_en[{$id}]">${description_en.trim()}</textarea>
                        </div>
                    <div class="col-md-4">
                        <label>{{__('RU')}}</label>
                        <textarea class="form-control" rows="3" name="description_ru[{$id}]">${description_ru.trim()}</textarea>
                </div>
            `;
            jitqws_div.appendChild(div);
            select_jitqws.innerHTML = ''; // Очищаем найденные вопросы
            new_jitqw.value = ''; // Очищаем поле ввода
        }
    
        // Создание нового вопроса через AJAX
        function create_new_jitqw(value) {
            fetch('{{ route("jitqws.store") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ 
                    jit: value,
                    description_uk: value.trim()
                })
            })
            .then(response => response.json())
            .then(data => {
                add_jitqw(data.id, data.description_ru, data.description_uk, data.description_en);
                console.log(data);
                jitqws.push(data);
            })
            .catch(error => console.error('Error:', error));
        }
    
        // Привязываем функцию поиска к полю ввода
        new_jitqw.addEventListener('keyup', find_jitqws);
    </script>
    
@endsection