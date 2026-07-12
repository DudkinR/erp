@extends('layouts.app')

@section('content')
<style>
        /* Стиль для запобігання зміщення SVG шару */
        #routeLayer {
            pointer-events: none;
            width: 100%;
            height: 100%;
        }
    </style>
 <!-- Додаємо overflow: auto для появи повзунків та прибираємо обмеження висоти h-100 у самої карти -->
<div class="container-fluid position-relative p-0" style="height:100vh; overflow:auto;">
    <!-- Фонова карта -->
    <img id="cityMap" 
         src="{{ asset('images/city-map.png') }}" 
         alt="City Map" 
         style="display:block; min-width: 1200px; width: 100%; height: auto;">

    <!-- SVG шар для стрілок (повинен мати такі ж розміри, як і карта) -->
    <svg id="routeLayer" 
         class="position-absolute top-0 start-0" 
         width="100%" height="100%" 
         style="pointer-events:none; min-width: 1200px;"></svg>
</div>


    <!-- Блоки керування -->
    <div id="ordersBlock" class="draggable-block bg-light bg-opacity-75 shadow rounded p-2 position-absolute" 
         style="top:20px; left:20px; width:200px; max-height:220px; overflow-y:auto;">
        <h6 class="text-primary">Маршрути</h6>
        <ul class="list-unstyled small mb-2">
            @foreach($routes as $route)
                <li data-from="{{ $route->from->x }},{{ $route->from->y }}"
                    data-to="{{ $route->to->x }},{{ $route->to->y }}"
                    data-car="{{ $route->car->name }}">
                    {{ $route->from->name }} - {{ $route->to->name }} 
                    ({{ \Carbon\Carbon::parse($route->time)->format('H:i') }})
                </li>
            @endforeach
        </ul>
        <button class="btn btn-sm btn-primary w-100" data-bs-toggle="modal" data-bs-target="#routesModal">
            Керувати маршрутами
        </button>
    </div>

    <div id="settingsBlock" class="draggable-block bg-warning bg-opacity-75 shadow rounded p-2 position-absolute" 
         style="top:20px; right:20px; width:200px; max-height:220px; overflow-y:auto;">
        <h6 class="text-dark">Налаштування</h6>
        <button class="btn btn-sm btn-outline-dark w-100 mb-2" data-bs-toggle="modal" data-bs-target="#addCarModal">
            Додати машину
        </button>
        <button type="button" class="btn btn-sm btn-outline-dark w-100 mb-2" data-bs-toggle="modal" data-bs-target="#dutyCarModal">
            Поставити на дежурство
        </button>
    </div>

    <div id="feedbackBlock" class="draggable-block bg-info bg-opacity-75 shadow rounded p-2 position-absolute" 
         style="bottom:20px; left:20px; width:200px; max-height:180px; overflow-y:auto;">
        <h6 class="text-white">Зворотній зв'язок</h6>
        <textarea class="form-control form-control-sm mb-2" rows="2" placeholder="Send your message..."></textarea>
        <button class="btn btn-sm btn-light w-100">Надіслати</button>
    </div>

    <div id="vehiclesBlock" class="draggable-block bg-success bg-opacity-75 shadow rounded p-2 position-absolute" 
         style="bottom:20px; right:20px; width:200px; max-height:220px; overflow-y:auto;">
        <h6 class="text-white mb-2">Машини</h6>
        @foreach($types as $type)
            <p class="small fw-bold text-white mb-1">
                @if($type->name === 'Легковий') 🚗
                @elseif($type->name === 'Мікроавтобус') 🚐
                @elseif($type->name === 'Автобус') 🚌
                @endif
                {{ $type->name }}
            </p>
            <ul class="list-unstyled ms-2 mb-1">
                @foreach($cars->where('type_id', $type->id) as $car)
                    <li class="text-light car-item small"
                        data-id="{{ $car->id }}" 
                        data-name="{{ $car->name }}" 
                        data-seats="{{ $car->seats }}" 
                        data-gov-number="{{ $car->gov_number }}"
                        data-features="{{ $car->features }}"
                        data-type="{{ $car->type_id }}"
                        data-condition="{{ $car->condition_id }}">
                        {{ $car->name }} <span class="badge bg-info">{{ $car->seats }}</span>
                    </li>
                @endforeach
            </ul>
        @endforeach
    </div>
</div>




  <!-- Модальне вікно редагування машини -->
      <div class="modal fade" id="editCarModal" tabindex="-1" aria-labelledby="editCarModalLabel" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header bg-success text-white">
              <h5 class="modal-title" id="editCarModalLabel">Редагувати машину</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
          <form id="editCarForm" method="POST" action="">
              @csrf
              @method('PUT')
              <div class="modal-body">
                <div class="mb-3">
                  <label for="carName" class="form-label">Назва</label>
                  <input type="text" class="form-control" id="carName" name="name" required>
                </div>
                <div class="mb-3">
                  <label for="govNumber" class="form-label">Держ. номер</label>
                  <input type="text" class="form-control" id="govNumber" name="gov_number" required>
                </div>
                <div class="mb-3">
                  <label for="carSeats" class="form-label">Кількість місць</label>
                  <input type="number" class="form-control" id="carSeats" name="seats" min="1" required>
                </div>
                <div class="mb-3">
                  <label for="carFeatures" class="form-label">Особливості</label>
                  <textarea class="form-control" id="carFeatures" name="features" rows="2"></textarea>
                </div>
                <div class="mb-3">
                  <label for="carType" class="form-label">Тип</label>
                  <select class="form-select" id="carType" name="type_id">
                    @foreach($types as $type)
                      <option value="{{ $type->id }}">{{ $type->name }}</option>
                    @endforeach
                  </select>
                </div>
                  <div class="mb-3">
                    <label for="carCondition" class="form-label">Стан</label>
                    <select class="form-select" id="carCondition" name="condition_id">
                      @foreach($conditions as $condition)
                        <option value="{{ $condition->id }}">{{ $condition->name }}</option>
                      @endforeach
                    </select>
                  </div>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Закрити</button>
                <button type="submit" class="btn btn-success">Зберегти</button>
              </div>
            </form>
          </div>
        </div>
      </div>
  <!-- Модальне вікно Додати машину-->
  <div class="modal fade" id="addCarModal" tabindex="-1" aria-labelledby="addCarModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header bg-warning">
        <h5 class="modal-title" id="addCarModalLabel">Додати машину</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <form method="POST" action="{{ route('taxi.storecar') }}">
        @csrf
        <div class="modal-body">
          <div class="mb-3">
            <label for="carName" class="form-label">Назва/Марка</label>
            <input type="text" class="form-control" id="carName" name="name" required>
          </div>
          <div class="mb-3">
            <label for="govNumber" class="form-label">Держ. номер</label>
            <input type="text" class="form-control" id="govNumber" name="gov_number" required>
          </div>
          <div class="mb-3">
            <label for="seats" class="form-label">Кількість місць</label>
            <input type="number" class="form-control" id="seats" name="seats" min="1" required>
          </div>
          <div class="mb-3">
            <label for="features" class="form-label">Особливості</label>
            <textarea class="form-control" id="features" name="features" rows="2"></textarea>
          </div>
          <div class="mb-3">
            <label for="type" class="form-label">Тип</label>
            <select class="form-select" id="type" name="type_id" required>
                <option value="">Виберіть тип</option>
                @foreach($types as $type)
                    <option value="{{ $type->id }}">{{ $type->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="mb-3">
            <label for="condition" class="form-label">Стан</label>
            <select class="form-select" id="condition" name="condition_id" required>
                <option value="">Виберіть стан</option>
                @foreach($conditions as $condition)
                    <option value="{{ $condition->id }}">{{ $condition->name }}</option>
                @endforeach
            </select>
        </div>
        </div>
         
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Закрити</button>
          <button type="submit" class="btn btn-warning">Зберегти</button>
        </div>
      </form>
    </div>
  </div>
  </div>
  <!-- Модальне вікно Керування маршрутами-->
  <div class="modal fade" id="routesModal" tabindex="-1" aria-labelledby="routesModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title" id="routesModalLabel">Керування маршрутами</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <form method="POST" action="{{ route('taxiroutes.store') }}" id="routeForm">
        @csrf 
      <div class="modal-body">
        <!-- Форма створення/редагування маршруту -->
        <form id="routeForm">
          <div class="row mb-3">
            <div class="col-md-6">
              <label class="form-label">Звідки (назва)</label>
              <select class="form-select" name="from_id" id="fromSelect">
                <!-- об’єкти підвантажуються з БД -->
                @foreach($objects as $object)
                  <option value="{{ $object->id }}">{{ $object->name }}</option>
                @endforeach
              </select>
            </div>
            <div class="col-md-6">
              <label class="form-label">Куди (назва)</label>
              <select class="form-select" name="to_id" id="toSelect">
                <!-- об’єкти підвантажуються з БД -->
                @foreach($objects as $object)
                  <option value="{{ $object->id }}">{{ $object->name }}</option>
                @endforeach
              </select>
            </div>
          </div>

          <div class="row mb-3">
            <div class="col-md-6">
              <label class="form-label">Дата</label>
              <input type="date" class="form-control" name="date"
              value="{{ \Carbon\Carbon::today()->format('Y-m-d') }}"
              required>
            </div>
            <div class="col-md-6">
              <label class="form-label">Час</label>
              <input type="time" class="form-control" name="time"
              value="{{ \Carbon\Carbon::now()->format('H:i') }}"
              required>
            </div>
          </div>

          <div class="mb-3">
            <label class="form-label">Машина</label>
            <select class="form-select" name="car_id" id="carSelect">
              <!-- машини підвантажуються з БД -->
              @foreach($cars as $car)
                @if($car->condition && $car->condition->name === 'На дежурстве')
                <option value="{{ $car->id }}">
                  {{ $car->name }} ({{ $car->seats }} місць)
                </option>
                @endif
              @endforeach
            </select>
          </div>

          <div id="carInfo" class="alert alert-info d-none"></div>
        
      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Закрити</button>
        <button type="submit" class="btn btn-primary" id="saveRouteBtn">Зберегти маршрут</button>
      </div>
    </form>
    </div>
  </div>
  </div>
  <!-- Модальне вікно створення об’єкта -->
  <div class="modal fade" id="createObjectModal" tabindex="-1" aria-labelledby="createObjectModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title" id="createObjectModalLabel">Створити новий об’єкт</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <form method="POST" action="{{ route('objects.store') }}">
        @csrf
        <div class="modal-body">
          <div class="mb-3">
            <label for="objectName" class="form-label">Назва об’єкта</label>
            <input type="text" class="form-control" id="objectName" name="name" required>
          </div>
          <div class="mb-3">
            <label for="objectX" class="form-label">Координата X</label>
            <input type="number" class="form-control" id="objectX" name="x" readonly>
          </div>
          <div class="mb-3">
            <label for="objectY" class="form-label">Координата Y</label>
            <input type="number" class="form-control" id="objectY" name="y" readonly>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Закрити</button>
          <button type="submit" class="btn btn-primary">Зберегти</button>
        </div>
      </form>
    </div>
  </div>
  </div>
  <!-- Модальне вікно постановки на дежурство -->
  <div class="modal fade" id="dutyCarModal" tabindex="-1" aria-labelledby="dutyCarModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content border-0 shadow-lg">
      
      <!-- Заголовок -->
      <div class="modal-header bg-warning text-dark">
        <h5 class="modal-title fw-bold" id="dutyCarModalLabel">🚓 Поставити машину на дежурство</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <!-- Форма -->
      <form id="dutyCarForm" method="POST" action="{{ route('cars.duty') }}">
        @csrf
        @method('POST')

        <div class="modal-body">
          @foreach($types as $type)
            <p class="fw-bold text-primary mt-3 mb-2">
              @if($type->name === 'Легковий') 🚗
              @elseif($type->name === 'Мікроавтобус') 🚐
              @elseif($type->name === 'Автобус') 🚌
              @endif
              {{ $type->name }}
            </p>

            @foreach($cars->where('type_id', $type->id) as $car)
              <div class="card mb-3 border-0 shadow-sm">
                <div class="card-body">
                  <!-- Назва машини -->
                  <label class="fw-bold d-block mb-2" for="car{{ $car->id }}">
                    {{ $car->name }} <span class="text-muted">({{ $car->gov_number }})</span>
                    <span class="badge bg-info ms-2">{{ $car->seats }} місць</span>
                  </label>
                 
                  <!-- Радіо для стану -->
                  <div class="d-flex flex-wrap gap-3 ms-3">
                    <input type="hidden" name="car_ids[]" value="{{ $car->id }}">
                    <select name="condition_ids[]">
                        @foreach($conditions as $condition)
                            <option value="{{ $condition->id }}"
                            @if($car->condition_id == $condition->id) selected @endif
                            >{{ $condition->name }}</option>
                        @endforeach
                    </select>
                  </div>

                  <!-- Приховані поля -->

                </div>
              </div>
            @endforeach
          @endforeach
        </div>

        <!-- Футер -->
        <div class="modal-footer bg-light">
          <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Закрити</button>
          <button type="submit" class="btn btn-warning fw-bold">💾 Зберегти</button>
        </div>
      </form>
    </div>
  </div>
  </div>
<script>
  const cars = @json($cars);
  const objects = @json($objects);
  // Перетягування блоків
  document.querySelectorAll('.draggable-block').forEach(block => {
      let offsetX, offsetY, isDragging = false;

      const savedPosition = localStorage.getItem(block.id);
      if (savedPosition) {
          const { top, left } = JSON.parse(savedPosition);
          block.style.top = top;
          block.style.left = left;
      }

      block.addEventListener('mousedown', e => {
          isDragging = true;
          offsetX = e.clientX - block.offsetLeft;
          offsetY = e.clientY - block.offsetTop;
          block.style.cursor = 'grabbing';
      });

      document.addEventListener('mousemove', e => {
          if (!isDragging) return;
          block.style.left = (e.clientX - offsetX) + 'px';
          block.style.top = (e.clientY - offsetY) + 'px';
      });

      document.addEventListener('mouseup', () => {
          if (isDragging) {
              localStorage.setItem(block.id, JSON.stringify({
                  top: block.style.top,
                  left: block.style.left
              }));
          }
          isDragging = false;
          block.style.cursor = 'grab';
      });
  });
  // Вся логіка модалки всередині DOMContentLoaded
  document.addEventListener("DOMContentLoaded", function() {
      const modalElement = document.getElementById('createObjectModal');
      const createObjectModal = new bootstrap.Modal(modalElement);

      const cityMap = document.getElementById('cityMap');
      
      cityMap.addEventListener('click', function(e) {
          // Перевіряємо чи натиснуто Ctrl
          if (!e.ctrlKey) return;
          const rect = cityMap.getBoundingClientRect();          
          // Позиція кліку всередині відображуваного елемента картинки
          const displayX = e.clientX - rect.left;
          const displayY = e.clientY - rect.top;          
          // Розрахунок коефіцієнта масштабу (Реальний розмір файлу / Поточний розмір на екрані)
          const scaleX = cityMap.naturalWidth / rect.width;
          const scaleY = cityMap.naturalHeight / rect.height;          
          // Точні координати в пікселях оригінального зображення
          const actualX = displayX * scaleX;
          const actualY = displayY * scaleY;
          // Записуємо округлені значення у приховані поля
          document.getElementById('objectX').value = Math.round(actualX);
          document.getElementById('objectY').value = Math.round(actualY);
          createObjectModal.show();
      });



        // Збереження маршруту
        const saveBtn = document.getElementById("saveRouteBtn");
        if (saveBtn) {
            saveBtn.addEventListener("click", function() {
                const form = document.getElementById("routeForm");
                const formData = new FormData(form);

                const routeData = {};
                formData.forEach((value, key) => routeData[key] = value);

                console.log("Дані маршруту:", routeData);
                // fetch('/taxiroutes', { method:'POST', body: formData })
            });
        }
    });

  document.addEventListener("DOMContentLoaded", function() {
      const routeLayer = document.getElementById("routeLayer");

      // Вішаємо обробник на всі <li> у списку маршрутів
    document.querySelectorAll("#ordersBlock li").forEach(item => {
        item.addEventListener("click", function() {
            // Очищаємо попередні стрілки
            const routeLayer = document.getElementById('routeLayer');
            const cityMap = document.getElementById('cityMap');
            routeLayer.innerHTML = "";

            // Розраховуємо поточний коефіцієнт масштабу екрана відносно оригінального файлу карти
            const rect = cityMap.getBoundingClientRect();
            const currentScaleX = rect.width / cityMap.naturalWidth;
            const currentScaleY = rect.height / cityMap.naturalHeight;

            // Отримуємо оригінальні координати та назву машини
            const [origFromX, origFromY] = this.dataset.from.split(",").map(Number);
            const [origToX, origToY] = this.dataset.to.split(",").map(Number);
            const carName = this.dataset.car;

            // Перераховуємо координати під поточний розмір екрана
            const fromX = origFromX * currentScaleX;
            const fromY = origFromY * currentScaleY;
            const toX = origToX * currentScaleX;
            const toY = origToY * currentScaleY;

            // Додаємо маркер стрілки
            const defs = document.createElementNS("http://www.w3.org/2000/svg", "defs");
            defs.innerHTML = `
              <marker id="arrowhead" markerWidth="10" markerHeight="7" 
                      refX="10" refY="3.5" orient="auto">
                <polygon points="0 0, 10 3.5, 0 7" fill="red" />
              </marker>`;
            routeLayer.appendChild(defs);

            // Малюємо лінію
            const line = document.createElementNS("http://www.w3.org/2000/svg", "line");
            line.setAttribute("x1", fromX);
            line.setAttribute("y1", fromY);
            line.setAttribute("x2", toX);
            line.setAttribute("y2", toY);
            line.setAttribute("stroke", "red");
            line.setAttribute("stroke-width", "2");
            line.setAttribute("marker-end", "url(#arrowhead)");
            routeLayer.appendChild(line);

            // Середина лінії
            const midX = (fromX + toX) / 2;
            const midY = (fromY + toY) / 2;

            // Іконка машини (кружок)
            const carIcon = document.createElementNS("http://www.w3.org/2000/svg", "circle");
            carIcon.setAttribute("cx", midX);
            carIcon.setAttribute("cy", midY);
            carIcon.setAttribute("r", 10);
            carIcon.setAttribute("fill", "blue");
            routeLayer.appendChild(carIcon);

            // Назва машини
            const text = document.createElementNS("http://www.w3.org/2000/svg", "text");
            text.setAttribute("x", midX + 15);
            text.setAttribute("y", midY + 5);
            text.setAttribute("fill", "black");
            text.setAttribute("font-size", "14");
            text.textContent = carName;
            routeLayer.appendChild(text);
        });
    });
  });
  document.addEventListener("DOMContentLoaded", function() {
      const editCarModal = new bootstrap.Modal(document.getElementById('editCarModal'));
      const editCarForm = document.getElementById('editCarForm');

      document.querySelectorAll('.car-item').forEach(item => {
          item.addEventListener('click', function() {
              const carId = this.dataset.id;
              const carName = this.dataset.name;
              const carSeats = this.dataset.seats;
              const carType = this.dataset.type;
              const govNumber = this.dataset.govNumber;
                const features = this.dataset.features;
                const condition = this.dataset.condition;

              // Заповнюємо форму
              document.getElementById('carName').value = carName;
              document.getElementById('carSeats').value = carSeats;
              document.getElementById('carType').value = carType;
              document.getElementById('govNumber').value = govNumber;
              document.getElementById('carFeatures').value = features;
              document.getElementById('carCondition').value = condition;

              // Оновлюємо action форми з реальним ID
              editCarForm.action = `/cars/${carId}`;

              // Відкриваємо модальне вікно
              editCarModal.show();
          });
      });
  });
  document.addEventListener("DOMContentLoaded", function() {
      const dutyCarModal = new bootstrap.Modal(document.getElementById('dutyCarModal'));
      const dutyCarForm = document.getElementById('dutyCarForm');

    
  });
</script>
@endsection
