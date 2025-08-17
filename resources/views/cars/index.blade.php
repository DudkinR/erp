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
            <h1>{{__('Cars')}}</h1>
                <a class="btn btn-light w-100" href="{{ route('cars.create') }}">{{__('Create')}}</a>
            </div>
        </div>

        <div class="container" id="showCarType"></div>

        <script>
       const cars = @json($cars);
const types = @json($allTypes);
function showCar(id) {
    const car = cars.find(car => car.id === id);
    if (!car) return '';
    const carShowUrl = `/cars/${car.id}`;
    const condition = types.find(type => type.id === car.condition_id);
    return `
        <div class="row mt-3">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                         ${car.name}
                    </div>
                    <div class="card-body">
                        <p> ${car.gov_number}</p>
                        <p>${condition ? condition.name : ''}</p>
                        @if(Auth::user()->hasRole('admin'))
                        <a href="${carShowUrl}" class="btn btn-primary">{{__('Show')}}</a>
                        <a href="/cars/${car.id}/edit" class="btn btn-warning">{{__('Edit')}}</a>
                        <form action="/cars/${car.id}" method="POST" style="display: inline;">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-danger" type="submit">{{__('Delete')}}</button>
                        </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    `;
}

function findAllTypes() {
    const CarTypes = {};
    cars.forEach(car => {
        if (car.type_id && !CarTypes[car.type_id]) {
            const type = types.find(type => type.id === car.type_id);
            if (type) {
                CarTypes[car.type_id] = type.name;
            }
        }
    });
    // sort by name
    return Object.keys(CarTypes).sort().reduce((obj, key) => {
        obj[key] = CarTypes[key];
        return obj;
    }, {});
    
}

function showType(id) {
    const carTypeName = findAllTypes()[id];
    if (!carTypeName) return ''; // Перевірка наявності типу

    // Початковий HTML-контент з назвою типу
    let htmlContent = `<div class="card mt-3">
                        <div class="card-header">
                            <p>
                                ${carTypeName}
                                 @if(Auth::user()->hasRole('admin'))
                                <a href="/cars/create?type_id=${id}" class="btn btn-primary float-right">{{__('Create')}}</a>
                                @endif
                            </p>
                        </div>
                        <div class="card-body">
                            <div class="row">`; // Початок рядка для триколоночного розміщення

    // Лічильник для відстеження кількості колонок у рядку
    let columnCount = 0;

    cars.forEach(car => {
        if (car.type_id === id) {
            htmlContent += `<div class="col-md-4 mb-3">`; // Колонка для кожного автомобіля
            htmlContent += showCar(car.id);
            htmlContent += `</div>`;

            columnCount++;

            // Закрити рядок і відкрити новий після трьох колонок
            if (columnCount % 3 === 0) {
                htmlContent += `</div><div class="row">`;
            }
        }
    });

    htmlContent += `</div></div></div>`; // Закрити рядок і картку
    return htmlContent;
}


function showCarType() {
    const showCarTypeContainer = document.getElementById('showCarType');
    const carTypes = findAllTypes();

    Object.keys(carTypes).forEach(typeId => {
        showCarTypeContainer.innerHTML += showType(Number(typeId));
    });
}

showCarType();

</script>
        
@endsection