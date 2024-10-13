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
                                <label for="name_ru">{{__('Name RU')}}</label>
                                <input type="text" class="form-control" id="name_ru" name="name_ru" >
                            </div>
                        </div>
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
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="description_ru">{{__('Description RU')}}</label>
                                <textarea class="form-control" id="description_ru" name="description_ru" rows="3"></textarea>
                            </div>
                        </div>
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
                                <input type="text" class="form-control" id="new_jitqw" name="new_jitqw">
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
        const jitqws = @json($jitqws);
        // find new_jitqw  and show in   select_jitqws with button add to jitqws (id, description_ru, description_uk, description_en)

        const new_jitqw = document.getElementById('new_jitqw');
        const select_jitqws = document.getElementById('select_jitqws');
        const jitqws_div = document.getElementById('jitqws');
        const add_jitqws = (id) => {
            const jitqw = jitqws.find(jitqw => jitqw.id == id);
            const div = document.createElement('div');
            div.innerHTML = `
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group
                    @if($errors->has('jitqws')) border border-danger @endif
                    ">
                        <label for="jitqws[${id}][description_ru]">{{__('Description RU')}}</label>
                        <textarea class="form-control" id="jitqws[${id}][description_ru]" name="jitqws[${id}][description_ru]" rows="3" required>${jitqw.description_ru}</textarea>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="jitqws[${id}][description_uk]">{{__('Description UK')}}</label>
                        <textarea class="form-control" id="jitqws[${id}][description_uk]" name="jitqws[${id}][description_uk]" rows="3" required>${jitqw.description_uk}</textarea>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="jitqws[${id}][description_en]">{{__('Description EN')}}</label>
                        <textarea class="form-control" id="jitqws[${id}][description_en]" name="jitqws[${id}][description_en]" rows="3" required>${jitqw.description_en}</textarea>
                    </div>
                </div>
            </div>
            ";
            jitqws_div.appendChild(div);
        }
        var jitqws_to_container =[];
        function find_jitqws() {
            const value = new_jitqw.value;
            if (value.length > 2) {
            var words = value.split(' ');
          
            for (var i = 0; i < jitqws.length; i++) {
                var jitqw = jitqws[i];
                var match = true;
                for (var j = 0; j < words.length; j++) {
                    if (jitqw.description_ru.indexOf(words[j]) == -1 && jitqw.description_uk.indexOf(words[j]) == -1 && jitqw.description_en.indexOf(words[j]) == -1) {
                        match = false;
                        break;
                    }
                }
                if (match) {
                    jitqws_to_container.push(jitqw);
                }
            }                
         };

         function show_jitqws() {
            select_jitqws.innerHTML = '';
            for (var i = 0; i < jitqws_to_container.length; i++) {
                var jitqw = jitqws_to_container[i];
                var div = document.createElement('div');
                div.innerHTML = `
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group" onclick="add_jitqws(${jitqw.id})">
                            <label>${jitqw.description_ru}</label>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group
                        @if($errors->has('jitqws')) border border-danger @endif
                        ">
                            <label>${jitqw.description_uk}</label>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group
                        @if($errors->has('jitqws')) border border-danger @endif
                        ">
                            <label>${jitqw.description_en}</label>
                        </div>
                    </div>
                </div>
                `;
                select_jitqws.appendChild(div);
            }
        }
        new_jitqw.addEventListener('input', find_jitqws);
        setInterval(show_jitqws, 10);

               


    </script>
@endsection