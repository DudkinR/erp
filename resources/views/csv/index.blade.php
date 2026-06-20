@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h1>{{ __('CSV Processing') }}</h1>
                
                <!-- Форма завантаження та налаштування -->
               <form id="csvForm" method="POST" action="{{ route('csv.process') }}" enctype="multipart/form-data">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <div class="form-group">
                        <label for="file">{{ __('File csv') }}</label>
                        <input type="file" class="form-control" id="file" name="file" required>
                    </div>

                    <div class="form-group">
                        <label for="type_of_file">{{ __('Type of file') }}</label>
                        <select class="form-control" id="type_of_file" name="type_of_file">
                            <option value="windows-1251">Windows-1251</option>
                            <option value="utf-8" selected>UTF-8</option>
                        </select>
                    </div>

                    <!-- Сюди за допомогою JS будуть підвантажуватися стовпці -->
                    <div id="mapping-container" style="display: none;" class="mt-4">
                        <h3>{{ __('Type of functions and column inputs') }}</h3>
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>{{ __('Column in CSV') }}</th>
                                        <th>{{ __('Action / Function') }}</th>
                                    </tr>
                                </thead>
                                <tbody id="columns-list">
                                    <!-- Динамічні рядки -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="form-group mt-3">
                        <button type="submit"  class="btn btn-secondary"> send </button>
                    <!-- Кнопки керування -->
                    <div class="form-group mt-3">
                        <button type="button" id="btn-analyze" class="btn btn-secondary">
                            {{ __('Analyze Columns') }}
                        </button>
                        <button type="button" id="btn-process" class="btn btn-primary" style="display: none;">
                            {{ __('Process & Download') }}
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>


    <script>
       // 2. Крок перший: Аналіз колонок файлу через чистий JS (Fetch)
$('#btn-analyze').click(function() {
    var fileInput = document.getElementById('file');
    if (!fileInput.files.length) {
        alert('Please select a file first.');
        return;
    }

    var formData = new FormData(document.getElementById('csvForm'));

    fetch("{{ route('csv.analyze') }}", {
    method: "POST",
    body: formData,
    redirect: "manual", // Забороняє браузеру тихо перетворювати POST на GET при редиректах
    headers: {
        'X-Requested-With': 'XMLHttpRequest',
        'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
    }
})
    .then(async response => {
        if (!response.ok) {
            const errorData = await response.json();
            throw new Error(errorData.message || 'Error analyzing file');
        }
        return response.json();
    })
    .then(data => {
        $('#columns-list').empty();
        
        // Будуємо список селектів для кожної знайденої колонки
        data.headers.forEach(function(header) {
            var options = '';
            $.each(data.functions, function(key, label) {
                options += `<option value="${key}">${label}</option>`;
            });

            var row = `<tr>
                <td><strong>${header}</strong></td>
                <td>
                    <select name="actions[${header}]" class="form-control">
                        ${options}
                    </select>
                </td>
            </tr>`;
            $('#columns-list').append(row);
        });

        // Зберігаємо шлях до тимчасового файлу
        if(!$('#temp_file_path').length) {
            $('#csvForm').append(`<input type="hidden" id="temp_file_path" name="temp_file_path" value="${data.path}">`);
        } else {
            $('#temp_file_path').val(data.path);
        }

        $('#mapping-container').fadeIn();
        $('#btn-analyze').hide();
        $('#btn-process').show();
    })
    .catch(error => {
        alert(error.message);
    });
});

    </script>
@endsection
