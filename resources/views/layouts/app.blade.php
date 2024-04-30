<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PPAPP</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>

</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <a class="navbar-brand" href="{{ route('home') }}">PPAPP</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item active">
                    <a class="nav-link" href="{{ route('home') }}">{{__('Home')}}</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('goals.index') }}">{{__('Goals')}}</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('structure.index') }}">{{__('Structure')}} </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('personal.index') }}">{{__('personal')}}</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('funs.index') }}">{{__('funs')}}</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('criteria.index') }}">{{__('Criteria')}}</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('facts.index') }}">{{__('Facts')}}</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('cats.index') }}">{{__('Category')}}</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('docs.index') }}">{{__('Document')}}</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('projects.index') }}">{{__('Projects')}}</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('problems.index') }}">{{__('Problems')}}</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('imports.index') }}">{{__('Import')}}</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('stages.index') }}">{{__('Stages')}}</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('steps.index') }}">{{__('Step')}}</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('controls.index') }}">{{__('Controls')}}</a>
                </li>

                
            </ul>
        </div>
    </nav>
    @yield('content')
    
</body>

</html>