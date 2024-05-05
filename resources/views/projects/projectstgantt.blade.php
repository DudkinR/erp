@extends('layouts.app')
@section('content')
<link href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet" type="text/css">
<link href="/js/gantt/css/style.css" type="text/css" rel="stylesheet">
<link href="//cdnjs.cloudflare.com/ajax/libs/prettify/r298/prettify.min.css" rel="stylesheet" type="text/css">

<style type="text/css">
    body {
        font-family: Helvetica, Arial, sans-serif;
        font-size: 13px;
        padding: 0 0 50px 0;
    }
    h1 {
        margin: 40px 0 20px 0;
    }
    h2 {
        font-size: 1.5em;
        padding-bottom: 3px;
        border-bottom: 1px solid #DDD;
        margin-top: 50px;
        margin-bottom: 25px;
    }
    table th:first-child {
        width: 150px;
    }
    .github-corner:hover .octo-arm {
        animation: octocat-wave 560ms ease-in-out
    }
    @keyframes octocat-wave {
        0%, 100% {
            transform: rotate(0)
        }
        20%, 60% {
            transform: rotate(-25deg)
        }
        40%, 80% {
            transform: rotate(10deg)
        }
    }
    @media (max-width:500px) {
        .github-corner:hover .octo-arm {
            animation: none
        }
        .github-corner .octo-arm {
            animation: octocat-wave 560ms ease-in-out
        }
    }
</style>

    <div class="container">
        <div class="row">
            <div class="col-md-12">
            <h1>{{__('Gant')}}</h1>
                <a class="text-right
                " href="{{ route('projects.index') }}">Back</a>
            </div>  
        </div>
        <div class="row">
            <div class="col-md-4">
                {{$project->name}}

            </div>
            <div class="col-md-8">
                {{$project->description}}
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">{{__('Gant')}}</div>
                    <div class="card-body">
                      <div class="gantt"></div>
                    </div>
               </div>
            </div>
        </div>
   </div>
   <script src="/js/gantt/js/jquery.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/jquery-cookie/1.4.1/jquery.cookie.min.js"></script>
    <script src="/js/gantt/js/jquery.fn.gantt.js"></script>
    <script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/prettify/r298/prettify.min.js"></script>
    <script>
        $(function() {
            "use strict";
<?php 
// // fillable fields `id`, `project_id`, `stage_id`, `step_id`, `dimension_id`, `control_id`, `deadline_date`, `status`, `responsible_position_id`, `dependent_task_id`, `parent_task_id`, `real_start_date`, `real_end_date`, `created_at`, `updated_at`
$demoSource = [];
foreach ($tasks as $task) {
    $control = \App\Models\Control::find($task->control_id);
    $step = \App\Models\Step::find($task->step_id);
    $stage = \App\Models\Stage::find($task->stage_id);
    $dimension = \App\Models\Dimension::find($task->dimension_id);
    $customClass = 'ganttGreen';
    if ($task->status == 'active') {
        if($task->deadline_date < date('Y-m-d')){
            $customClass = 'ganttRed';
        } else {
            $customClass = 'ganttGreen';
        }
    
    } 
    elseif ($task->status == 'pending') {
        $customClass = 'ganttOrange';
    }
     elseif ($task->status == 'completed') {
        $customClass = 'ganttGray';
    }
    
    $demoSource[] = [
        'name' => '<a href="'.route('stages.show', $task->stage_id).'">'.$stage->name.'</a>',
        'desc' => '<a href="'.route('controls.show', $task->control_id).'">'.$control->name.'</a>',        
        'values' => [ [
            'from' => strtotime($task->real_start_date),
            'to' => strtotime($task->deadline_date),
            'label' =>  $dimension->name,
            'customClass' => $customClass,
            'dimension_description' => $dimension->description,
        ]]
    ];
}

?>
var demoSource = <?php echo json_encode($demoSource, JSON_PRETTY_PRINT); ?>;
//console.log(demoSource); // Output to browser console for debugging

          
            // shifts dates closer to Date.now()
            var offset = new Date().setHours(0, 0, 0, 0) -
                new Date(demoSource[0].values[0].from).setDate(35);
            for (var i = 0, len = demoSource.length, value; i < len; i++) {
                value = demoSource[i].values[0];
                value.from += offset;
                value.to += offset;
            }

            $(".gantt").gantt({
                source: demoSource,
                navigate: "scroll",
                scale: "weeks",
                maxScale: "months",
                minScale: "hours",
                itemsPerPage: 100,
                scrollToToday: false,
                useCookie: true,
                onItemClick: function(data) {
                    console.log(data);
                    alert("Go");
                },
                onAddClick: function(dt, rowId) {
                    console.log(dt);
                    console.log(rowId);
                    alert("Empty space clicked - add an item!");
                },
                onRender: function() {
                    if (window.console && typeof console.log === "function") {
                        console.log("chart rendered");
                    }
                }
            });

            $(".gantt").popover({
                selector: ".bar",
                title: function _getItemText() {
                    return this.textContent;
                },
                container: '.gantt',
                content:"Looks",
                trigger: "hover",
                placement: "auto right"
            });

            prettyPrint();

        });
    </script>
@endsection