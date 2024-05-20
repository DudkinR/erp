@extends('layouts.app')
@section('content')
<?php
$clients = App\Models\Client::all();
?>
    <div class="container">
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif
        <div class="row">
            <div class="col-md-12">
            <h1>{{__('Projects')}}</h1>
                <a class="text-right" href="{{ route('projects.create') }}">{{__('New project')}}</a>
                <button class="btn btn-warning" onclick="refresh()" > {{__('refresh')}} </button>
            </div>
        </div>
        <div class="row">
            <div class="col-md-4">
                <div class="form-group mb-2">
                    <label for="current_state">{{__('Current State')}}</label>
                    <select name="current_state" id="current_state" class="form-control" onchange="renderProjects()">  
                        <option
                        @if(isset($_SESSION['current_state']) && $_SESSION['current_state'] == 'all')
                            selected
                        @endif
                         value="all">{{__('All not closed')}}</option>
                        <option value="working"
                        @if(isset($_SESSION['current_state']) && $_SESSION['current_state'] == 'working')
                            selected
                        @endif
                        >{{__('Working')}}</option>
                        <option value="Очікується погодження"
                        @if(isset($_SESSION['current_state']) && $_SESSION['current_state'] == 'Очікується погодження')
                            selected
                        @endif
                        >{{__('Очікується погодження')}}</option>
                        <option value="Виготовлення та комплектація"
                        @if(isset($_SESSION['current_state']) && $_SESSION['current_state'] == 'Виготовлення та комплектація')
                            selected
                        @endif
                        >{{__('Виготовлення та комплектація')}}</option>
                        <option value="Готовий до забезпечення"
                        @if(isset($_SESSION['current_state']) && $_SESSION['current_state'] == 'Готовий до забезпечення')
                            selected
                        @endif
                        >{{__('Готовий до забезпечення')}}</option>
                        <option value="Готовий до відвантаження"
                        @if(isset($_SESSION['current_state']) && $_SESSION['current_state'] == 'Готовий до відвантаження')
                            selected
                        @endif
                        >{{__('Готовий до відвантаження')}}</option>
                        <option value="У процесі відвантаження"
                        @if(isset($_SESSION['current_state']) && $_SESSION['current_state'] == 'У процесі відвантаження')
                            selected
                        @endif
                        >{{__('У процесі відвантаження')}}</option>
                        <option value="Очікується оплата (після відвантаження)"
                        @if(isset($_SESSION['current_state']) && $_SESSION['current_state'] == 'Очікується оплата (після відвантаження)')
                            selected
                        @endif
                        >{{__('Очікується оплата (після відвантаження)')}}</option>
                        <option value="Готовий до закриття"
                        @if(isset($_SESSION['current_state']) && $_SESSION['current_state'] == 'Готовий до закриття')
                            selected
                        @endif
                        >{{__('Готовий до закриття')}}</option>
                         <option value="Закритий"
                        @if(isset($_SESSION['current_state']) && $_SESSION['current_state'] == 'Закритий')
                            selected
                        @endif
                         >{{__('Закритий')}}</option>
                        <option value="Чернетка"
                        @if(isset($_SESSION['current_state']) && $_SESSION['current_state'] == 'Чернетка')
                            selected
                        @endif
                        >{{__('Чернетка')}}</option>
                         <option value="empty"
                        @if(isset($_SESSION['current_state']) && $_SESSION['current_state'] == 'empty')
                            selected
                        @endif
                         >{{__('Empty')}}</option>
                    </select>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group mb-2">
                    <label for="client">{{__('Client')}}</label>
                    <select name="client" id="client" class="form-control" onchange="renderProjects()">
                    </select>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group mb-2">
                    <label for="sort_way">{{__('Sort straght')}}</label>
                    <select name="sort_way" id="sort_way" class="form-control" onchange="renderProjects()">
                        <option value="desc"
                        @if(isset($_SESSION['sort_way']) && $_SESSION['sort_way'] == 'desc')
                            selected
                        @endif
                        >{{__('Descending')}}</option>
                        <option value="asc"
                        @if(isset($_SESSION['sort_way']) && $_SESSION['sort_way'] == 'asc')
                            selected
                        @endif
                        >{{__('Ascending')}}</option>
                        
                    </select>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-3">
                <button id="sort_date" class="btn btn-primary" onclick="sort_date=1; sort_priority=0;  renderProjects();">{{__('Sort by date')}}</button>
                <button id="sort_priority" class="btn btn-primary"  onclick="sort_date=0; sort_priority=1;  renderProjects();">{{__('Sort by priority')}}</button>
            </div>
            <div class="col-md-4">
                <!-- find form -->
                <div class="form-group mb-2">
                    <input type="text" name="search" id="search" class="form-control" onkeyup="findProjects()"
                    @if(isset($_SESSION['search']))
                        value="{{$_SESSION['search']}}"
                    @endif
                    placeholder="{{__('Search')}}">
                </div>
            </div>
        </div>
        <div class="container" id="projects">
        </div>
    </div>
    <script>
        const PRS = @json($projects);
        var PRSW = PRS;
        <?php 
        $clientslist = [];
        foreach($clients as $client)
        {
            $clientslist[$client->id] = $client->name;
        }
        // sort by name
        asort($clientslist);
        ?>
        var clients = @json($clientslist);
        // sort by values
        clients = Object.fromEntries(Object.entries(clients).sort(([,a],[,b]) => a.localeCompare(b)));
        // form id client as clients
        const clientsSelect = document.getElementById('client');
        const option = document.createElement('option');
        option.value = 0;
        option.innerText = '{{__('All clients')}}';
        clientsSelect.appendChild(option);
        for (const id in clients) {
            const option = document.createElement('option');
            option.value = id;
            option.innerText = clients[id];
            clientsSelect.appendChild(option);
        }
       // console.log(PRSW);
        const projectsDiv = document.getElementById('projects');
        let sort_date = 0;
        let sort_priority = 0;
        function findProjects() {
            let search = document.getElementById('search').value;
            // ищем в названии и описании проекта в номере проекта
            PRSW = PRS.filter(project => project.name.includes(search) || project.description.includes(search) || project.number.includes(search));
            renderProjects();
        }
        function renderProjects() {
            let projects = PRSW;
            projects = show_clients(projects);
            projects = filter_by_current_state(projects);
            projects = sort_by_date(projects);
            projects = sort_by_priority(projects);
            if (sort_date == 1) {
                projects = sort_by_date(projects);
            }
            if (sort_priority == 1) {
                projects = sort_by_priority(projects);
            }
            projectsDiv.innerHTML = '';
            projects.forEach(project => {
                const projectDiv = document.createElement('div');
                 // Вычисляем временной интервал между текущей датой и датой выполнения проекта
                const executionDate = new Date(project.execution_period);
                const currentDate = new Date();
                const daysUntilExecution = Math.ceil((executionDate - currentDate) / (1000 * 60 * 60 * 24));

                // Определяем класс карточки в зависимости от временного интервала
                let cardClass = "card";
                if(project.current_state !== 'Закритий' && project.current_state !== 'Готовий до закриття' && project.current_state !== 'Очікується оплата (після відвантаження)')
                {
                    if (daysUntilExecution <= 0) {
                        cardClass += " bg-danger"; // Если период выполнения прошел
                    } else if (daysUntilExecution <= 3) {
                        cardClass += " bg-warning"; // Если осталось меньше 3 дней до периода выполнения
                    }
                }
                let class_name = 'btn btn-primary';
                if(project.problems_count>0)
                {
                    class_name = 'btn btn-danger';
                }
                projectDiv.innerHTML = `
                    <div class="${cardClass}">
                        <div class="card-body">
                            <div class="row">    
                                <div class="col-md-4">
                                    <h4 class="card-title text-danger">{{__('General information')}}</h4>    
                                    <h5 class="card-title text-primary">${project.name}</h5>
                                    <p class="card-text">${project.description}</p> 
                                    <p class="card-text">${project.priority}</p>
                                    <p class="card-text">${project.number}</p>
                                    <p class="card-text">${project.date}</p>
                                    <p class="card-text">${project.amount}</p>
                                    <p class="card-text">${clients[project.client]}</p>
                                    <p class="card-text">${project.current_state}</p>
                                    <p class="card-text">${project.execution_period}</p>
                                    <p class="card-text">
                                    Count of problems: ${project.problems_count}  </p>
                                    <hr>
                                    <a href="/projects/${project.id}/edit" class="btn btn-warning"> {{__('Edit')}}</a>
                                    <a href="/projects/${project.id}" class="btn btn-success"> {{__('Show')}}</a>
                                    <a href="/projectstgantt/${project.id}" class="btn btn-primary"> {{__('Gantt')}}</a>
                                 
                                       </div>
                                <div class="col-md-4 border">
                                    <h4 class="card-title text-danger">
                                        {{__('Docs')}}
                                    </h4>     
                                    <ul>
                                        ${project.docs.map(doc => `<li>
                                            <a href="/docs/${doc.id}">${doc.name}</a>
                                            </li>`).join('')}
                                    </ul>  
                                    <a href="/addDocs?project_id=${project.id}" class="btn btn-primary">{{__('Add docs')}}</a>
                                  
                                    </div>
                                <div class="col-md-4">
                                    <h4 class="card-title text-danger">{{__('Tasks')}}</h4>
                                    <ul>
                                        ${project.tasks.map(task => `<li>
                                            <a href="/tasks/${task.id}">${task.name}</a> - ${task.status}
                                        </li>`).join('')}
                                    </ul>
                                    <hr>
                                    <a href="/tasks/create?project_id=${project.id}" class="btn btn-warning">{{__('Add task')}}</a>
                                    <a href="/problems/create?project_id=${project.id}" class= "${class_name}">{{__('Add problem')}}</a>
                                    <a href="/problems?project_id=${project.id}" class="btn btn-warning">{{__('Show problems')}}</a>
                               
                                     </div>
                            </div>   
                        </div>
                    </div>
                `;
                projectsDiv.appendChild(projectDiv);
            });
        }
        function show_clients(projects) {
         let selected_client = document.getElementById('client').value;
            if (selected_client == 0) {
                return projects;
            }
            return projects.filter(project => project.client == selected_client);
        }
        function sort_by_date(projects) {

            let sort_way = document.getElementById('sort_way').value;
            if (sort_way == 'asc') {
                return projects.sort((a, b) => new Date(a.date) - new Date(b.date));
            }
            return projects.sort((a, b) => new Date(b.date) - new Date(a.date));
        }
        function sort_by_priority(projects) {
            let sort_way = document.getElementById('sort_way').value;
            if (sort_way == 'asc') {
                return projects.sort((a, b) => a.priority - b.priority);
            }
            return projects.sort((a, b) => b.priority - a.priority);
        }
        // current_state
        function filter_by_current_state(projects) {
            let current_state = document.getElementById('current_state').value;
            if (current_state == 'all') {
                return projects;
            }
            else if (current_state == 'working') {
                // all without closed, draft and empty
                return projects.filter(project => project.current_state !== 'Закритий'  && project.current_state !== 'Чернетка' && project.current_state !== '');
            }
            return projects.filter(project => project.current_state == current_state);
        }
        renderProjects();
        function refresh() {
            PRSW = PRS;
            renderProjects();
        }
    </script>
@endsection