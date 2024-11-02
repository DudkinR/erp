@extends('layouts.app')
@section('content')
<style>
    #risk {
        -webkit-text-size-adjust: 100%;
        -webkit-tap-highlight-color: transparent;
        --blue: #007bff;
        --indigo: #6610f2;
        --purple: #6f42c1;
        --pink: #e83e8c;
        --red: #dc3545;
        --orange: #fd7e14;
        --yellow: #ffc107;
        --green: #28a745;
        --teal: #20c997;
        --cyan: #17a2b8;
        --white: #fff;
        --gray: #6c757d;
        --gray-dark: #343a40;
        --primary: #007bff;
        --secondary: #6c757d;
        --success: #28a745;
        --info: #17a2b8;
        --warning: #ffc107;
        --danger: #dc3545;
        --light: #f8f9fa;
        --dark: #343a40;
        --breakpoint-xs: 0;
        --breakpoint-sm: 576px;
        --breakpoint-md: 768px;
        --breakpoint-lg: 992px;
        --breakpoint-xl: 1200px;
        --font-family-sans-serif: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, "Noto Sans", sans-serif, "Apple Color Emoji", "Segoe UI Emoji", "Segoe UI Symbol", "Noto Color Emoji";
        --font-family-monospace: SFMono-Regular, Menlo, Monaco, Consolas, "Liberation Mono", "Courier New", monospace;
        font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, "Noto Sans", sans-serif, "Apple Color Emoji", "Segoe UI Emoji", "Segoe UI Symbol", "Noto Color Emoji";
        font-size: 1rem;
        font-weight: 400;
        line-height: 1.5;
        color: #212529;
        text-align: left;
        -webkit-user-drag: element;
        user-select: none;
        box-sizing: border-box;
        display: flex;
        flex-wrap: wrap;
        margin-right: -15px;
        margin-left: -15px;
        box-shadow: 0 .5rem 1rem rgba(0, 0, 0, .15) !important;
        margin-bottom: .5rem !important;
        padding: .25rem !important;
        position: fixed;
        z-index: 1;
        top: 0px;
        right: 0px;
        overflow-x: hidden;
    }    
    #possible_risk, #real_risk {
        padding: 1rem;
        text-align: center;
    }
    </style>
    <form action="{{ route('risks.risksPrintBrief') }}" method="post" id = "form_current" target="_blank">
        @csrf
    <div class="row" id="risk">
        <div class="col-md-6" id="possible_risk">
            <!-- Контейнер для ризику 'possible_risk' -->
        </div>
        <div class="col-md-6" id="real_risk">
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
            <h1 class="text-center">{{__('Experiences')}}</h1>
                <a class="btn btn-light w-100" href="{{ route('experiences') }}">{{__('Add')}}</a>

                
                    
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
                    <div class="container" id="optional_item">
                        <div class="row">
                        @foreach($briefs->where('type', '1') as $brief)
                            
                                <div class="col-md-6">
                                    <input type="checkbox" name="br_action[{{$brief->id}}]" id="br_action_{{$brief->id}}" value="{{$brief->id}}">
                                    @if($brief->name_uk !== '')
                                        {!!$brief->name_uk!!}
                                    @elseif($brief->name_en !== '')
                                        {!!$brief->name_en!!}
                                    @endif
                                    {!!$brief->name_ru!!}
                                    
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

                                    @if($brief->name_uk !== '')
                                        {!!$brief->name_uk!!}
                                    @elseif($brief->name_en !== '')
                                        {!!$brief->name_en!!}
                                    @endif
                                        {!!$brief->name_ru!!}                                    
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
                                    @if($brief->name_uk !== '')
                                        {!!$brief->name_uk!!}
                                    @elseif($brief->name_en !== '')
                                        {!!$brief->name_en!!}
                                    @endif
                                        {!!$brief->name_ru!!}                                    
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
                                    @if($brief->name_uk !== '')
                                        {!!$brief->name_uk!!}
                                    @elseif($brief->name_en !== '')
                                        {!!$brief->name_en!!}
                                    @endif
                                        {!!$brief->name_ru!!}                                    
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
        let risk=0;
        let reasons= {};

        // знаходим ці заходи в об'єкті briefs там знаходимо reasons та рахуэмо який вплив на ризик має сумарне виконання заходів якщо не маємо то нулівий вплив
        // в перемеенной reasons бачимо якій reason впливає на ризик від 0 до 1 
        function countRealRisk(risk) {
            // Get all checked checkboxes with name starting with 'br_action'
            let checkboxes = document.querySelectorAll('input[name^="br_action"]');
            let checkedIds = [];

            checkboxes.forEach(checkbox => {
                if (checkbox.checked) {
                    checkedIds.push(checkbox.value);
                }
            });

            // Calculate the risk impact based on selected actions in the `briefs` object
            let totalImpact = 0;
            checkedIds.forEach(id => {
                let brief = briefs.find(item => item.id === parseInt(id));
                if (brief && brief.reason) {
                    totalImpact += brief.reason;  // Assuming `reason` contains the risk impact factor (from 0 to 1)
                }
            });

            // Calculate the real risk as a function of total impact; adjust as needed
            return risk * (1 - totalImpact);
        }
       // send post form_current to {{route('risks.currentRisk')}}  and get response
       async function getRisk() {
        let form = document.getElementById('form_current');
        let formData = new FormData(form);

        try {
            const response = await fetch("{{route('risks.currentRisk')}}", {
                method: 'POST',
                body: formData
            });
            const data = await response.json();
            
           // console.log(data.risk);
            
            // Update experiences and display risk
            experiences = data.experiences;
            showExperiences();
            showRisk(data.risk.result);
            risk = data.risk.result;
            document.getElementById('risk').value = risk;
            // Update reasons if they were returned
            if (data.risk.reasons) {
                reasons = data.risk.reasons;
                document.getElementById('reasons').value = JSON.stringify(reasons);

            }
        } catch (error) {
            console.error('Error fetching risk data:', error);
        }
    }
      //  console.log(briefs);
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
   

      

    </script>
@endsection