<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <style>
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 11px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        th, td {
            border: 1px solid #000;
            padding: 3px 5px;
            text-align: left;
            vertical-align: top;
        }
        th {
            background-color: #f0f0f0;
            font-weight: bold;
        }
        .package {
            margin-bottom: 4px;
            padding: 3px;
            background: #f9f9f9;
            border: 1px solid #ddd;
        }
        .package-title {
            font-weight: bold;
            font-size: 10px;
        }
        .small {
            font-size: 9px;
            color: #555;
        }
    </style>
</head>
<body>
    <h2>Архів документів</h2>
    <p>Загальна кількість документів: {{ count($data['documents']) }}</p>

    <table>
        <thead>
            <tr>
                <th>Назва</th>
                <th>Дата реєстрації</th>
                <th>Код</th>
                <th>Інвентарний № розробника</th>
                <th>Об'єкт</th>
                <th>Пакети</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data['documents'] as $doc)
                <tr>
                    <td>{{ $doc['foreign_name'] ?? '' }}</td>
                    <td>{{ $doc['reg_date'] ?? '' }}</td>
                    <td>{{ $doc['code'] ?? '' }}</td>
                    <td>{{ $doc['inventory'] ?? '' }}</td>
                    <td>{{ $doc['object'] ?? '' }}</td>
                    <td>
                        @if(!empty($doc['packages']))
                            @foreach($doc['packages'] as $pkg)
                                <div class="package">
                                    <div class="package-title">{{ $pkg['foreign_name'] ?? '' }}</div>
                                    @if(!empty($pkg['national_name']))
                                        <div class="small">{{ $pkg['national_name'] ?? '' }}</div>
                                    @endif
                                </div>
                            @endforeach
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
