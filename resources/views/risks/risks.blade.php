@extends('layouts.app')
@section('content')
<style>
    /* General container for risk section */
    #risk {
        --primary-color: #007bff;
        --risk-before-color: #ffc107; /* Yellow for possible risk */
        --risk-after-color: #28a745; /* Green for real risk */
        
        display: flex;
        justify-content: space-around;
        padding: 1rem;
        background-color: #f8f9fa;
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
        border-radius: 8px;
    }

    .risk-container {
        padding: 1rem;
        border-radius: 8px;
        box-shadow: 0 0.3rem 0.8rem rgba(0, 0, 0, 0.1);
        text-align: center;
        width: 100%;
    }

    #possible_risk {
        background-color: var(--risk-before-color);
        color: #343a40;
    }

    #real_risk {
        background-color: var(--risk-after-color);
        color: #fff;
    }

    .risk-title {
        font-weight: bold;
        font-size: 1.1rem;
        margin-bottom: 0.5rem;
        text-transform: uppercase;
    }

    /* Container boxes with padding and soft shadows */
    .container {
        background: #f8f9fa;
        padding: 1.5rem;
        border-radius: 10px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        margin-bottom: 1rem;
    }

    /* Text alignment and spacing for titles */
    h1, h2, h4 {
        text-align: center;
        font-weight: 600;
        color: var(--dark);
        margin-bottom: 1rem;
    }

    /* Button Styling */
    .btn {
        font-size: 1rem;
        padding: 0.75rem 1rem;
        border-radius: 5px;
        transition: background-color 0.3s ease;
    }

    .btn-light {
        background-color: var(--white);
        color: var(--primary);
        border: 1px solid var(--primary);
    }

    .btn-light:hover {
        background-color: var(--primary);
        color: var(--white);
    }

    /* Form inputs */
    .form-control {
        border-radius: 5px;
        border: 1px solid #ced4da;
        transition: border-color 0.3s;
    }

    .form-control:focus {
        border-color: var(--primary);
        box-shadow: 0 0 5px rgba(0, 123, 255, 0.5);
    }

    /* Checkbox styling */
    input[type="checkbox"] {
        accent-color: var(--primary);
        width: 1.25rem;
        height: 1.25rem;
    }

    /* Labels */
    label {
        font-weight: 500;
        color: var(--dark);
    }

    /* Shadow and padding for sections within the form */


    /* Alerts styling */
    .alert {
        font-weight: 600;
        margin-bottom: 1rem;
        border-radius: 5px;
    }
</style>

    <form action="{{ route('risks.risksPrintBrief') }}"  method="POST" id = "form_current" target="_blank">
        @csrf
        <div class="container" id="risk">
            <div class="risk-container" id="possible_risk">
                <div class="risk-title">{{ __('Risk Before Actions') }}</div>
                <!-- Контейнер для ризику 'possible_risk' -->
            </div>
            <div class="risk-container" id="real_risk">
                <div class="risk-title">{{ __('Risk After Actions') }}</div>
                <!-- Контейнер для ризику 'real_risk' -->
            </div>
        </div>
    


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
            <div class="col-md-4">
                risks
            <h1 class="text-center">{{__('Experiences')}}</h1>
                <a class="btn btn-light w-100" href="{{ route('risks.create') }}">{{__('Add')}}</a>    
                <div class="container bg-info">
                    <div class="row">
                        <div class="col-md-12">
                            <label for="equipments">{{__('Use Equipments')}}</label>
                            <select class="form-control" size=5 name="equipments[]" id="equipments" multiple>
                                @foreach($equipments as $equipment)
                                    <option value="{{$equipment->id}}">{{$equipment->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <label for="systems">{{__('Use Systems')}}</label>
                            <select class="form-control" size=5 name="systems[]" id="systems" multiple>
                                @foreach($systems as $system)
                                    <option value="{{$system->id}}">{{$system->uk}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <label for="action">{{__('Main Action')}}</label>
                            <select class="form-control" size=5 name="action" id="action" >
                                @foreach($actions as $action)
                                    <option value="{{$action->name}}">{{$action->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <label for="addition_actions">{{__('Addition Actions (JIT)')}}</label>
                            <select class="form-control" size=5 name="addition_actions[]" id="addition_actions" multiple>
                                @foreach($addition_actions as $action)
                                    <option value="{{$action->id}}">{{$action->name_ru}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-12">
                            <label for="full_name">{{__('Full Name Action')}}</label>
                            <input type="text" class="form-control" name="full_name" id="full_name">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <label for="tn">{{__('Tab num')}}</label>
                            <input type="text" class="form-control" name="tn" id="tn" value="{{Auth::user()->tn}}">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <label for="fio">{{__('FIO')}}</label>
                            <input type="text" class="form-control" name="fio" id="fio" value="{{Auth::user()->personal->fio}}">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <label for="place">{{__('Place')}}</label>
                            <input type="text" class="form-control" name="place" id="place">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <label for="date">{{__('Date')}}</label>
                            <input type="date" class="form-control" name="date" id="date" value="{{date('Y-m-d')}}">
                        </div>
                    </div>
                
                    <div class="row">
                        <div class="col-md-12">
                            <button type="submit" class="btn btn-light w-100">{{__('Print')}}</button>
                        </div>
                    </div>
                </div>
                
            </div>

            <div class="col-md-8">
                <div class="container" id='brief'>
                    <h1 class="text-center">{{__('Risks')}}</h1>
                    <h4>
                        <ul>
                            <li id='sistems_names_show'></li>
                            <li id='equipments_names_show'></li>
                            <li id='full_name_show'></li>
                            <li id='addition_actions_show'></li>
                        </ul>
                    </h4>

                    <h2 class="text-center">{{__('Brief Actions')}}</h2>
                    <hr>
                    <h3 class="text-center">{{__('Addition Brief Actions')}}</h3>
                    <div class="container" id="optional_item">
                        <div class="row">
                        @foreach($briefs->where('type', '1') as $brief)
                            
                                <div class="col-md-6">
                                    <input type="checkbox" name="br_action[{{$brief->id}}]" id="br_action_{{$brief->id}}" value="{{$brief->id}}">
                                    {!! $brief->name_uk ?: ($brief->name_en ?: $brief->name_ru) !!}

                                    
                                </div>
                            
                        @endforeach
                        </div>
                    </div>
                    <div class="container"  id="experiences">
                        <div class="row">
                            <div class="col-md-12">
                                <h2 class="text-center">{{__('Experiences')}}</h2>
                            </div>
                        </div>
                    </div>
                    <div class="container" id="before">
                        <h2 class="text-center">{{__('Before')}}</h2>
                        @foreach($briefs->where('type', '2')->where('functional', '1') as $brief)
                            <div class="row">
                                <div class="col-md-12">
                                    <input type="checkbox" name="br_action[{{$brief->id}}]" id="br_action_{{$brief->id}}" value="{{$brief->id}}" checked  style="opacity: 1; pointer-events: none;">

                                    {!! $brief->name_uk ?: ($brief->name_en ?: $brief->name_ru) !!}
                                  
                                </div>
                            </div>
                        @endforeach   
                    </div>                    
                    <div class="container" id="during">
                        <h2 class="text-center">{{__('During')}}</h2>
                        @foreach($briefs->where('type', '2')->where('functional', '2') as $brief)
                            <div class="row">
                                <div class="col-md-12">
                                    <input type="checkbox" name="br_action[{{$brief->id}}]" id="br_action_{{$brief->id}}" value="{{$brief->id}}" checked  style="opacity: 1; pointer-events: none;">
                                    {!! $brief->name_uk ?: ($brief->name_en ?: $brief->name_ru) !!}
                                  
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="container" id="after ">
                        <h2 class="text-center">{{__('After')}}</h2>
                        @foreach($briefs->where('type', '2')->where('functional', '3') as $brief)
                            <div class="row">
                                <div class="col-md-12">
                                    <input type="checkbox" name="br_action[{{$brief->id}}]" id="br_action_{{$brief->id}}" value="{{$brief->id}}" checked  style="opacity: 1; pointer-events: none;">
                                    {!! $brief->name_uk ?: ($brief->name_en ?: $brief->name_ru) !!}
                                 
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
    <input type="hidden" name="risk" value="0" id="risk">
    <input type="hidden" name="reasons" value="" id="reasons">

    </form>
    <script>
const briefs = @json($briefs); 
let risk = 0;
let realRiskReasons = []; //  рельний вплив на ризик різних факторів
let reasons = {}; // назначенний вплив на ризик Вид дій

// Function to calculate real risk based on selected actions
function countRealRisk(initialRisk) {
    console.log("Initial Risk:", initialRisk);

    const checkboxes = document.querySelectorAll('input[name^="br_action"]');
    const checkedIds = Array.from(checkboxes)
        .filter(cb => cb.checked)
        .map(cb => parseInt(cb.value));

    let realRiskReasons = {}; // Об'єкт для зберігання ризиків

    // Вплив кожної обраної дії на ризик
    checkedIds.forEach(id => {
        const brief = briefs.find(item => item.id === id);
        if (brief && brief.reasons) {
            brief.reasons.forEach(reason => {
                if (!realRiskReasons[reason.id]) {
                    realRiskReasons[reason.id] = brief.risk;
                } else {
                    realRiskReasons[reason.id] += brief.risk;
                }
            });
        }
    });

    // Нормалізація ризиків (поділ на кількість дій)
    const normalizedRiskReasons = Object.keys(realRiskReasons).reduce((acc, key) => {
        acc[key] = realRiskReasons[key] / checkedIds.length;
        return acc;
    }, {});

    console.log("Normalized Risk Reasons:", normalizedRiskReasons);

    // Розрахунок реального ризику
    let currentRisk = initialRisk; // Початковий ризик

    Object.keys(normalizedRiskReasons).forEach(key => {
        const reasonImpact = normalizedRiskReasons[key];
        const reasonEfficiency = reasons[key];

        if (reasonImpact > 0 && reasonEfficiency > 0) {
            const reduction = currentRisk * reasonImpact * (reasonEfficiency / 100); // Зниження від попереднього значення
            currentRisk -= reduction; // Зменшуємо ризик
            console.log(
                `Reason ID: ${key}, Impact: ${reasonImpact}, Efficiency: ${reasonEfficiency}%, Reduction: ${reduction}, New Risk: ${currentRisk}`
            );
        }
    });

    currentRisk = Math.max(currentRisk, 0); // Гарантуємо, що ризик не стане від’ємним
    console.log("Final Real Risk:", currentRisk);

    return currentRisk;
}


// Function to show risk on the UI
function showRisk(currentRisk) {
    currentRisk = parseFloat(currentRisk.toFixed(2));
    const red = 7;
    const yellow = 5;

    let form = document.getElementById('possible_risk');
    form.className = ''; // Clear previous classes

    // Set class based on current risk level
    if (currentRisk > red) {
        form.classList.add('text-center', 'bg-danger');
    } else if (currentRisk > yellow) {
        form.classList.add('text-center', 'bg-warning');
    } else {
        form.classList.add('text-center', 'bg-success');
    }

    animateNumber(form, parseFloat(form.innerText) || 0, currentRisk, 1000); // Animate risk display

    let realRisk = parseFloat(countRealRisk(currentRisk).toFixed(2));
    let form2 = document.getElementById('real_risk');
    form2.className = ''; // Clear previous classes

    // Set class based on real risk level
    if (realRisk > red) {
        form2.classList.add('text-center', 'bg-danger');
    } else if (realRisk > yellow) {
        form2.classList.add('text-center', 'bg-warning');
    } else {
        form2.classList.add('text-center', 'bg-success');
    }

    animateNumber(form2, parseFloat(form2.innerText) || 0, realRisk, 1000); // Animate real risk display
}

// Function to animate the risk number display
function animateNumber(element, start, end, duration) {
    const range = end - start;
    const startTime = Date.now();

    function update() {
        const elapsed = Date.now() - startTime;
        const progress = Math.min(elapsed / duration, 1);
        const current = (start + range * progress).toFixed(2);
        element.innerText = current;

        if (progress < 1) {
            requestAnimationFrame(update);
        }
    }

    update();
}
// Функція для оновлення ризиків у відповідних контейнерах
function updateRiskDisplay(currentRisk) {
    //console.log(currentRisk);
     // Перетворення в число або встановлення значення за замовчуванням
     currentRisk = isNaN(parseFloat(currentRisk)) ? 10 : parseFloat(currentRisk);
    // Форматування ризику до двох знаків після коми
    currentRisk = parseFloat(currentRisk.toFixed(2));    
    // Визначаємо межі кольорів
    const redThreshold = 7;
    const yellowThreshold = 5;
    // Оновлення контейнера для можливого ризику
    const possibleRiskContainer = document.getElementById('possible_risk');
    possibleRiskContainer.className = ''; // Очищення класів
    if (currentRisk > redThreshold) {
        possibleRiskContainer.classList.add('text-center', 'bg-danger');
    } else if (currentRisk > yellowThreshold) {
        possibleRiskContainer.classList.add('text-center', 'bg-warning');
    } else {
        possibleRiskContainer.classList.add('text-center', 'bg-success');
    }
    possibleRiskContainer.innerText = `Risk Before Actions: ${currentRisk}`; // Оновлюємо текст
    // Обчислюємо реальний ризик і оновлюємо контейнер для реального ризику
    const realRisk = countRealRisk(currentRisk); // Обчислюємо реальний ризик
    const realRiskContainer = document.getElementById('real_risk');
    realRiskContainer.className = ''; // Очищення класів
    const formattedRealRisk = parseFloat(realRisk.toFixed(2));
    
    if (formattedRealRisk > redThreshold) {
        realRiskContainer.classList.add('text-center', 'bg-danger');
    } else if (formattedRealRisk > yellowThreshold) {
        realRiskContainer.classList.add('text-center', 'bg-warning');
    } else {
        realRiskContainer.classList.add('text-center', 'bg-success');
    }

    realRiskContainer.innerText = `Risk After Actions: ${formattedRealRisk}`; // Оновлюємо текст
}

// Функція, що викликається при зміні чекбоксів
async function getRisk() {
        let form = document.getElementById('form_current');
        let formData = new FormData(form);
        formData.append('_token', '{{ csrf_token() }}');
        formData.append('risk', risk);
        formData.append('reasons', JSON.stringify(reasons));
        // change url to "{{route('risks.currentRisk')}}"
        formData.append('experiences', JSON.stringify(experiences));       
        try {
            const response = await fetch("{{route('risks.currentRisk')}}", {
                method: 'POST',                         
                body: formData
            });
            const data = await response.json();
           //console.log(data.risk);
            // Update experiences and display risk
            experiences = data.experiences;
           // console.log(experiences);
            showExperiences();
            showRisk(data.risk.result);
            risk = data.risk.result;
            document.getElementById('risk').value = risk;
            // Update reasons if they were returned
            if (data.risk.reasons) {
                reasons = data.risk.reasons;
                document.getElementById('reasons').value = JSON.stringify(reasons);
                updateRiskDisplay(data.risk.result);
            }
        } catch (error) {
            console.error('Error fetching risk data:', error);
        }
    }

// Додаємо обробник подій до чекбоксів
const checkboxes = document.querySelectorAll('input[name^="br_action"]');
checkboxes.forEach(item => {
    item.addEventListener('change', getRisk); // Додаємо обробник події для кожного чекбоксу
});

// Приклад ініціалізації
updateRiskDisplay(5); // Викликаємо для початкового значення ризику
        var experiences;
     // show selected equipments in sistems_names_show 
        document.getElementById('equipments').addEventListener('change', function(){
            let selected = this.selectedOptions;
            let selectedText = '';
            for(let i = 0; i < selected.length; i++){
                selectedText += selected[i].text + ', ';
            }
            document.getElementById('equipments_names_show').innerText = selectedText;

        });  
        // show selected sistems in sistems_names_show
        document.getElementById('systems').addEventListener('change', function(){
            let selected = this.selectedOptions;
            let selectedText = '';
            for(let i = 0; i < selected.length; i++){
                selectedText += selected[i].text + ', ';
            }
            document.getElementById('sistems_names_show').innerText = selectedText;

        });
        // show selected full_name in full_name_show + Main Action
        document.getElementById('full_name').addEventListener('input', function(){
            const action = document.getElementById('action');
            const selectedAction = action.options[action.selectedIndex].text;
            document.getElementById('full_name_show').innerText = this.value + ' - ' + selectedAction;
    
        });
        // addition_actions_show  + selected addition_actions
        document.getElementById('addition_actions').addEventListener('change', function(){
            let selected = this.selectedOptions;
            let selectedText = '';
            for(let i = 0; i < selected.length; i++){
                selectedText += selected[i].text + ', ';
            }
            document.getElementById('addition_actions_show').innerText = selectedText;

        });
     

        // if change any input or select on page, call getRisk function
        document.querySelectorAll('input, select').forEach(item => {
            item.addEventListener('change', getRisk);
        });

        // change checkbox in briefs


        // show 10 first experiences in experiences div add rows
        function showExperiences() {
            let experiencesDiv = document.getElementById('experiences');
            experiencesDiv.innerHTML = `
                <div class="row">
                    <div class="col-md-12">
                        <h2 class="text-center">{{__('Experiences')}}</h2>
                    </div>
                </div>
            `;
            
            let i = 0;
            experiences.forEach(experience => {
                if (i < 4) {
                    let row = document.createElement('div');
                    row.classList.add('row', 'mb-3', 'p-2', 'border', 'border-secondary', 'rounded');

                    let col = document.createElement('div');
                    col.classList.add('col-md-12', 'd-flex', 'align-items-start');

                    // Вибір тексту залежно від наявності значень в об'єкті
                    let text;
                    if (experience.text_uk !== '') {
                        text = experience.text_uk;
                    } else if (experience.text_en !== '') {
                        text = experience.text_en;
                    } else {
                        text = experience.text_ru;
                    }

                    // Додавання чекбокса
                    let checkbox = document.createElement('input');
                    checkbox.type = 'checkbox';
                    checkbox.name = `experience[${experience.id}]`;
                    checkbox.id = `experience_${experience.id}`;
                    checkbox.value = experience.id;
                    checkbox.classList.add('me-2'); // Відступ між чекбоксом і текстом
                    if(i<3)
                        checkbox.checked = true; // Якщо потрібно, можна встановити як вибраний за замовчуванням
                    else
                        checkbox.checked = false;                   

                    col.appendChild(checkbox);
                    // Додаємо чекбокс безпосередньо до форми  id = "form_current"
                ///    document.getElementById('form_current').appendChild(checkbox);
                    


                    // Додавання тексту з абзацами
                    let textContainer = document.createElement('span');
                    textContainer.innerHTML = text.replace(/\n/g, '<br>');
                    col.appendChild(textContainer);

                    row.appendChild(col);
                    experiencesDiv.appendChild(row);

                    i++;
                }
            });
        }
    // show risk function
    /*
    function showRisk(currentRisk) {
        currentRisk = parseFloat(currentRisk.toFixed(2));
        const red = 7;
        const yellow = 5;

        let form = document.getElementById('possible_risk');
        form.className = ''; // Очищаємо попередній клас
        if (currentRisk > red) {
            form.classList.add('text-center', 'bg-danger');
        } else if (currentRisk > yellow) {
            form.classList.add('text-center', 'bg-warning');
        } else {
            form.classList.add('text-center', 'bg-success');
        }

        animateNumber(form, parseFloat(form.innerText) || 0, currentRisk, 1000); // Анімація 1 секунда

        let realRisk = parseFloat(countRealRisk(currentRisk).toFixed(2));
        let form2 = document.getElementById('real_risk');
        form2.className = ''; // Очищаємо попередній клас
        if (realRisk > red) {
            form2.classList.add('text-center', 'bg-danger');
        } else if (realRisk > yellow) {
            form2.classList.add('text-center', 'bg-warning');
        } else {
            form2.classList.add('text-center', 'bg-success');
        }

        animateNumber(form2, parseFloat(form2.innerText) || 0, realRisk, 1000); // Анімація 1 секунда
    }



    function animateNumber(element, start, end, duration) {
        let range = end - start;
        let startTime = Date.now();

        function update() {
            let elapsed = Date.now() - startTime;
            let progress = Math.min(elapsed / duration, 1);
            let current = (start + range * progress).toFixed(2);
            element.innerText = current;

            if (progress < 1) {
                requestAnimationFrame(update);
            }
        }

        update();
    }
   
*/
      

    </script>
@endsection