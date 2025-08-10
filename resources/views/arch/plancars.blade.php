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
            </div>
        </div>

        <div class="container" id="showCarType">
            <table class="table table-bordered">
                <thead>
                <tr>
                    <th rowspan="2"> {{__('Car')}}</th>
                    <th colspan="24">{{$date}} : {{__('Hours')}}</th>
                </tr>
                <tr> 
                    @for($i = 1; $i <= 24; $i++)
                        <th>{{ $i }}</th>
                    @endfor
                </tr>
                </thead>
                <tbody>
                    @php
                        $carType = null;
                    @endphp
                @foreach($cars as $car)
                   @php
                   if($carType && $carType->id !== $car->type_id) {
                        $carType = $allTypes->firstWhere('id', $car->type_id);
                        echo '<tr class="table-secondary"
                        ><td colspan="25"><h6>' . $carType->name . '</h6></td></tr>';
                    } elseif(!$carType) {
                        $carType = $allTypes->firstWhere('id', $car->type_id);
                        echo '<tr class="table-secondary"
                        ><td colspan="25"><h6>' . $carType->name . '</h6></td></tr>';
                    }
                    @endphp
                    <tr 
                    id="car-{{ $car->id }}" 
                    ondrop="drop(event)" 
                    ondragover="allowDrop(event)"
                    data-car-id="{{ $car->id }}"
                    >
                        <td>{{ $car->name }} <br><b>{{ $car->gov_number }}</b></td>
                        @for($i = 1; $i <= 24; $i++)
                            <td></td>
                        @endfor
                    </tr>
                @endforeach
                </tbody>
            </table>
            <div class="row">
                <div class="col-md-6">
                    <h1>{{ __('Drivers') }}</h1>
                    @foreach($drivers as $driver)
                        <a 
                            title="{{ $driver->positions[0]->name }}" 
                            id="driver-{{ $driver->id }}" 
                            draggable="true" 
                            ondragstart="dragd(event)"
                            style="cursor: pointer; color: black; background-color: #00ff00; border: 1px solid #ccc; padding: 5px; margin: 5px;"
                        >
                            {{ $driver->nickname }}
                        </a>, 
                    @endforeach
                </div>
                <div class="col-md-6">
                    <h1>{{ __('Carorders') }}</h1>
                    @foreach($carorders as $carorder)
                        <a 
                            title="{{ $carorder->typecar_id }}" 
                            id="carorder-{{ $carorder->id }}" 
                            draggable="true" 
                            ondragstart="dragw(event)"
                            style="cursor: pointer; color: black; background-color: #c0c0c0; border: 1px solid #ccc; padding: 5px; margin: 5px;"
                        >
                            {{ $carorder->title }}
                        </a>,
                    @endforeach
            </div>
        </div>
    </div>
</div>

    
    <div id="timeModal" class="modal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h2>{{ __('Set Usage Time') }}</h2>
                    <span onclick="closeModal()">&times;</span>
                </div>
                <form id="assignDriverForm" onsubmit="return false;">
                    <input type="hidden" id="modalDriverId" name="driver_id">
                    <input type="hidden" id="modalCarId" name="car_id">
                    
                    <label>{{ __('Start Time') }}</label>
                    <input type="time" id="startTime" name="start_date">
                    
                    <label>{{ __('End Time') }}</label>
                    <input type="time" id="endTime" name="end_date">
                    
                    <button type="button" onclick="assignDriver()">Submit</button>
                </form>
            </div>
        </div>
    </div>

<script>
  const cars = @json($cars);
const types = @json($allTypes);
const drivers = @json($drivers);

const carAssignments = {};  // Store car assignments to check for overlaps

function allowDrop(event) {
    event.preventDefault();
}

function dragd(event) {
    event.dataTransfer.setData("driver_id", event.target.id);
}
function dragw(event) {
    event.dataTransfer.setData("carorder_id", event.target.id);
}

function drop(event) {
    event.preventDefault();
    const driverId = event.dataTransfer.getData("driver_id");
    const carId = event.target.closest('tr').dataset.carId;

    showTimeModal(driverId, carId);
}

function showTimeModal(driverId, carId) {
    document.getElementById('modalDriverId').value = driverId.replace('driver-', '');
    document.getElementById('modalCarId').value = carId;
    document.getElementById('timeModal').style.display = 'block';
}

function closeModal() {
    document.getElementById('timeModal').style.display = 'none';
}

function assignDriver() {
    const carId = document.getElementById('modalCarId').value;
    const driverId = document.getElementById('modalDriverId').value;
    const startDate = new Date(document.getElementById('startDate').value);
    const endDate = new Date(document.getElementById('endDate').value);

    // Check for overlaps before submitting
    if (hasOverlap(carId, startDate, endDate)) {
        alert('This car is already assigned during the selected time.');
        return;
    }

    const formData = new FormData(document.getElementById('assignDriverForm'));
    fetch('{{route("assign-driver")}}', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            closeModal();
            updateCarStatus(data.car_id, data.start_date, data.end_date, driverId);
        } else {
            alert('Error assigning driver');
        }
    });
}

function hasOverlap(carId, startDate, endDate) {
    const assignments = carAssignments[carId] || [];
    for (let assignment of assignments) {
        const assignedStart = new Date(assignment.startDate);
        const assignedEnd = new Date(assignment.endDate);
        
        if (
            (startDate <= assignedEnd && startDate >= assignedStart) ||
            (endDate <= assignedEnd && endDate >= assignedStart) ||
            (startDate <= assignedStart && endDate >= assignedEnd)
        ) {
            return true;
        }
    }
    return false;
}

function updateCarStatus(carId, startDate, endDate, driverId) {
    const carRow = document.querySelector(`#car-${carId}`);
    const driverName = drivers.find(driver => driver.id == driverId).nickname;

    // Convert startDate and endDate to hours
    const startHour = new Date(startDate).getHours();
    const endHour = new Date(endDate).getHours();

    if (!carAssignments[carId]) carAssignments[carId] = [];

    carAssignments[carId].push({ startDate, endDate, driverName });

    for (let i = startHour; i <= endHour; i++) {
        const cell = carRow.cells[i + 1];
        cell.style.backgroundColor = 'gray';
        cell.title = `Driver: ${driverName}`; // Set tooltip to show driver on hover
    }
}


</script>
        
@endsection