<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <a class="navbar-brand" href="{{ route('home') }}">
       <img src="{{ asset('logo/logo_npp.png') }}" alt="logo" style="width: 50px;">
    </a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav">
            @auth
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    {{__('Projects')}}

                </a>
                <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                    <a class="dropdown-item" href="{{ route('projects.index') }}">{{__('List')}}</a>
                    <a class="dropdown-item" href="{{ route('projects.grantt') }}">{{__('Grantt')}}</a>
                   <a class="dropdown-item" href="{{ route('problems.index') }}">{{__('Problems')}}</a>
                   <a class="dropdown-item" href="{{ route('tasks.index') }}">{{__('Tasks')}}</a>
                  
                </div>
            </li>
           
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    {{__('Quality')}}
                </a>
                <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                    <a class="dropdown-item" href="{{ route('goals.index') }}"> {{__('Goals')}}</a>
                    <a class="dropdown-item" href="{{ route('objectives.index') }}">{{__('Objectives')}}</a>
                    
                    <a class="dropdown-item" href="{{ route('funs.index') }}">{{__('Functions')}}</a>
                    <a class="dropdown-item" href="{{ route('facts.index') }}">{{__('Facts')}}</a>
                    <a class="dropdown-item" href="{{ route('cats.index') }}">{{__('Category')}}</a>
                    <a class="dropdown-item" href="{{ route('stages.index') }}">{{__('Stages')}}</a>
                    <a class="dropdown-item" href="{{ route('steps.index') }}">{{__('Steps')}}</a>
                    <a class="dropdown-item" href="{{ route('controls.index') }}">{{__('Controls')}}</a>
                    <a class="dropdown-item" href="{{ route('dimensions.index') }}">{{__('Dimensions')}}</a> 
                    @if(Auth::user()->hasRole('quality-engineer','admin'))
                      <a class="dropdown-item" href="{{ route('imports.index') }}">{{__('Imports')}}</a>
                    @endif
                </div>
            </li>
          
          
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    {{__('Personal')}}
                </a>
                <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                    <a class="dropdown-item" href="{{ route('divisions.index') }}">{{__('Divisions')}}</a>
                    <a class="dropdown-item" href="{{ route('structure.index') }}"> {{__('Structure')}}</a>
                     <a class="dropdown-item" href="{{ route('personal.index') }}"> {{__('Personal')}}</a>
                    <a class="dropdown-item" href="{{ route('funs.index') }}">{{__('Funs')}}</a>
                    <a class="dropdown-item" href="{{ route('criteria.index') }}">{{__('Criteria')}}</a>
                 <a class="dropdown-item" href="{{ route('positions.index') }}">{{__('Positions')}}</a>
              </div>
            </li>  

                            
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    {{__('Magasines')}}
                </a>
                <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                    <a class="dropdown-item" href="{{ route('callings.index') }}">{{__('Callings')}}</a>
                    <a class="dropdown-item" href="{{ route('magasines.index') }}">{{__('Magasines')}}</a>
                    <a class="dropdown-item" href="{{ route('products.index') }}">{{__('Products')}}</a>
                    <a class="dropdown-item" href="{{ route('equipments.index') }}">{{__('Equipments')}}</a>
                    <a class="dropdown-item" href="{{ route('stores.index') }}">{{__('Stores')}}</a>
                    <a class="dropdown-item" href="{{ route('rooms.index') }}">{{__('Rooms')}}</a>
                    <a class="dropdown-item" href="{{ route('organomic.index') }}">{{__('Organomics')}}</a>
                </div>
            </li>
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    {{__('Documentation')}}
                </a>
                <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                    <a class="dropdown-item" href="{{ route('docs.index') }}">{{__('Documents')}}</a>
                    <a class="dropdown-item" href="{{ route('archives.index') }}">{{__('Archives')}}</a>
                </div>
            </li>


            @if(Auth::user()->hasRole('admin'))
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    {{__('Nomenclatures')}}
                </a>
                <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                    <a class="dropdown-item" href="{{ route('nomenclaturs.index') }}">{{__('Nomenclatures')}}</a>
                    <a class="dropdown-item" href="{{ route('types.index') }}">{{__('Types')}}</a>
                </div>
                

            </li>
            @endif
            @if(Auth::user()->hasRole('admin'))
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    {{__('Risks')}}
                </a>
                <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                    
                <a class="dropdown-item" href="{{ route('experiences') }}">{{__('Experiences')}}</a>
                    <a class="dropdown-item" href="{{ route('risks.index') }}">{{__('Risks')}}</a>
                    <a class="dropdown-item" href="{{ route('briefs.index') }}">{{__('Briefs')}}</a>
                    <a class="dropdown-item" href="{{ route('jits.index') }}">{{__('JITs')}}</a>
                    <a class="dropdown-item" href="{{ route('jitqws.index') }}">{{__('JITQws')}}</a>
                    <a class="dropdown-item" href="{{ route('callings.index') }}">{{__('Calling')}}</a>
                </div>
                

            </li>
            @endif
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    {{__('Profile')}}
                </a>
                <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                    <a class="dropdown-item" href="{{ route('profiles.index') }}">{{__('Profile')}}</a>
                    <a class="dropdown-item" href="{{ route('personal.index') }}">  {{__('Personal')}}</a>
                    <!-- logout -->
                    <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">{{__('Logout')}}</a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>

                </div>
            </li>
            @endauth
            @guest
            <li class="nav-item">
                <a class="nav-link" href="{{ route('login') }}">{{__('Login')}}</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('personal.index') }}"> {{__('Personal')}}</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('register') }}">{{__('Register')}}</a>
            </li>
            @endguest
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    {{__('Dictionary')}}
                </a>
                <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                    <a class="dropdown-item" href="{{ route('dictionary.index') }}">{{__('UK-EN-ru')}}</a>
                </div>
            </li>
        </ul>
        
    </div>
</nav>