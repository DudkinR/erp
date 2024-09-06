@extends('layouts.app')
@section('content')
<?php
$clients = App\Models\Client::orderBy('id', 'desc')->get();
?>
    <div class="container">
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif
        @if(Auth::user()->hasRole('moderator','admin','quality-engineer'))
        <div class="row">
            <div class="col-md-12">
            <h1>{{__('Projects')}}</h1>
                <a class="btn btn-info" href="{{ route('projects.create') }}">{{__('New project')}}</a>
                <button class="btn btn-warning" onclick="refresh()" > {{__('refresh')}} </button>
            </div>
        </div>
        @endif
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
        // Initializing projects and stages data from server-side variables
        const PRS = @json($projects);
        @php $stages = App\Models\Stage::orderBy('id', 'desc')->get(); @endphp
        const STS = @json($stages);
        let PRSW = [...PRS];
    
        // Generating a sorted clients list from PHP
        let clients = @json($clients->pluck('name', 'id')->sort()->toArray());
    
        // Populate client selection dropdown
        const clientsSelect = document.getElementById('client');
        addOption(clientsSelect, 0, '{{__('All clients')}}');
        Object.entries(clients).forEach(([id, name]) => addOption(clientsSelect, id, name));
    
        // Utility function to add options to a select element
        function addOption(select, value, text) {
            const option = document.createElement('option');
            option.value = value;
            option.innerText = text;
            select.appendChild(option);
        }
    
        // Main element for rendering projects
        const projectsDiv = document.getElementById('projects');
        let sortDate = 0;
        let sortPriority = 0;
    
        // Search and filter projects
        function findProjects() {
            const search = document.getElementById('search').value.toLowerCase();
            PRSW = PRS.filter(project =>
                project.name.toLowerCase().includes(search) ||
                project.description.toLowerCase().includes(search) ||
                project.number.toLowerCase().includes(search)
            );
            renderProjects();
        }
    
        // Render filtered and sorted projects
        function renderProjects() {
            let projects = showClients(PRSW);
            projects = filterByCurrentState(projects);
            projects = sortByDate(projects);
            projects = sortByPriority(projects);
    
            projectsDiv.innerHTML = '';
            projects.forEach(project => projectsDiv.appendChild(createProjectCard(project)));
        }
    
        // Create project card element
        function createProjectCard(project) {
            const projectDiv = document.createElement('div');
            projectDiv.className = getCardClass(project);
    
            projectDiv.innerHTML = `
                <div class="card-body">
                    <div class="row">
                        ${getGeneralInfoColumn(project)}
                        ${getDocsColumn(project)}
                        ${getStagesTasksColumn(project)}
                    </div>
                </div>
            `;
            return projectDiv;
        }
    
        // Get card class based on execution period and current state
        function getCardClass(project) {
            const executionDate = new Date(project.execution_period);
            const daysUntilExecution = Math.ceil((executionDate - new Date()) / (1000 * 60 * 60 * 24));
            let cardClass = "card";
    
            if (!['{{__("Closed")}}', '{{__("Ready for closure")}}', '{{__("Awaiting payment (post-shipment)")}}'].includes(project.current_state)) {
                if (daysUntilExecution <= 0) cardClass += " bg-danger"; // Execution period has passed
                else if (daysUntilExecution <= 3) cardClass += " bg-warning"; // Less than 3 days left until execution period
            }
            return cardClass;
        }
    
        // Generate general information column HTML
        function getGeneralInfoColumn(project) {
            return `
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
                    <p class="card-text">{{__('Count of problems')}}: ${project.problems_count}</p>
                    <hr>
                    @if(Auth::user()->hasRole('moderator','admin','quality-engineer'))
                    <a href="/projects/${project.id}/edit" class="btn btn-warning">{{__('Edit')}}</a>
                    @endif
                    <a href="/projects/${project.id}" class="btn btn-success">{{__('Show')}}</a>
                    <a href="/projectstgantt/${project.id}" class="btn btn-primary">{{__('Gantt')}}</a>
                </div>
            `;
        }
    
        // Generate documents column HTML
        function getDocsColumn(project) {
            return `
                <div class="col-md-4 border">
                    <h4 class="card-title text-danger">{{__('Docs')}}</h4>
                    <ul>
                        ${project.docs.map(doc => `<li><a href="/docs/${doc.id}">${doc.name}</a></li>`).join('')}
                    </ul>
                    @if(Auth::user()->hasRole('moderator','admin','quality-engineer'))
                    <a href="/addDocs?project_id=${project.id}" class="btn btn-primary">{{__('Add docs')}}</a>
                    @endif
                </div>
            `;
        }
    
        // Generate stages and tasks column HTML
        function getStagesTasksColumn(project) {
            const uniqueStages = getUniqueStages(project);
            const problemButtonClass = project.problems_count > 0 ? 'btn btn-danger' : 'btn btn-primary';
    
            return `
                <div class="col-md-4">
                    <h4 class="card-title text-danger">{{__('Stages')}}</h4>
                    <ul>
                        ${uniqueStages.map(stage => `<li><a href="/stage_tasks/${project.id}/${stage.stage_id}">${getStageName(stage.stage_id)}</a></li>`).join('')}
                    </ul>
                    <h4 class="card-title text-danger">{{__('Tasks')}}</h4>
                    <ul>
                        <li>{{__('Completed')}}: ${countTasks(project, 'completed')}</li>
                        <li>{{__('New')}}: ${countTasks(project, 'new')}</li>
                        <li>{{__('Problem')}}: ${countTasks(project, 'problem')}</li>
                    </ul>
                    <hr>
                    @if(Auth::user()->hasRole('moderator','admin','quality-engineer'))
                    <a href="/tasks/create?project_id=${project.id}" class="btn btn-warning">{{__('Add task')}}</a>
                    <a href="/problems/create?project_id=${project.id}" class="${problemButtonClass}">{{__('Add problem')}}</a>
                    @endif
                    <a href="/problems?project_id=${project.id}" class="btn btn-warning">{{__('Show problems')}}</a>
                </div>
            `;
        }
    
        // Get unique stages for a project
        function getUniqueStages(project) {
            return project.tasks.reduce((acc, stage) => {
                if (!acc.some(item => item.stage_id === stage.stage_id)) acc.push(stage);
                return acc;
            }, []);
        }
    
        // Get stage name by ID
        function getStageName(stageId) {
            return STS.find(st => st.id == stageId)?.name || '';
        }
    
        // Count tasks by status
        function countTasks(project, status) {
            return project.tasks.filter(task => task.status === status).length;
        }
    
        // Filter projects by selected client
        function showClients(projects) {
            const selectedClient = document.getElementById('client').value;
            return selectedClient == 0 ? projects : projects.filter(project => project.client == selectedClient);
        }
    
        // Sort projects by date
        function sortByDate(projects) {
            const sortWay = document.getElementById('sort_way').value;
            return projects.sort((a, b) => (sortWay === 'asc' ? new Date(a.date) - new Date(b.date) : new Date(b.date) - new Date(a.date)));
        }
    
        // Sort projects by priority
        function sortByPriority(projects) {
            const sortWay = document.getElementById('sort_way').value;
            return projects.sort((a, b) => (sortWay === 'asc' ? a.priority - b.priority : b.priority - a.priority));
        }
    
        // Filter projects by current state
        function filterByCurrentState(projects) {
            const currentState = document.getElementById('current_state').value;
            if (currentState === 'all') return projects;
            if (currentState === 'working') return projects.filter(project => !['{{__("Closed")}}', '{{__("Draft")}}', ''].includes(project.current_state));
            return projects.filter(project => project.current_state === currentState);
        }
    
        // Initial render
        renderProjects();
    
        // Refresh function to reset filters
        function refresh() {
            PRSW = [...PRS];
            renderProjects();
        }
    </script>
    
@endsection