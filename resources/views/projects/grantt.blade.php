@extends('layouts.app')
@section('content')
<link href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet" type="text/css">
<link href="/js/gantt/css/style.css" type="text/css" rel="stylesheet">
<link href="//cdnjs.cloudflare.com/ajax/libs/prettify/r298/prettify.min.css" rel="stylesheet" type="text/css">

<style type="text/css">
  body {
            font-family: Helvetica, Arial, sans-serif;
            font-size: 13px;
            padding: 0;
            margin: 0;
            height: 100%;
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
            animation: octocat-wave 560ms ease-in-out;
        }
        @keyframes octocat-wave {
            0%, 100% {
                transform: rotate(0);
            }
            20%, 60% {
                transform: rotate(-25deg);
            }
            40%, 80% {
                transform: rotate(10deg);
            }
        }
        @media (max-width:500px) {
            .github-corner:hover .octo-arm {
                animation: none;
            }
            .github-corner .octo-arm {
                animation: octocat-wave 560ms ease-in-out;
            }
        }
        .gantt {
            width: 100%;
            height: calc(100vh - 50px); /* Высота контейнера - 100% высоты окна браузера минус padding body */
            overflow: auto;
        }
</style>

    <div class="container">
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
      
      <?php
        $demoSource = [];
        foreach ($projects as $project) {
            $customClass='ganttGreen';
            if($project->execution_period < date('Y-m-d') && $project->current_state == 'в процесі'  ) {
                $customClass='ganttRed';
            }
            elseif($project->execution_period > date('Y-m-d') && $project->current_state == 'завершено'  ) {
                $customClass='ganttWhite';
            }

            $demoSource[] = [
                'name' =>'<a href="/projects/'.$project->id.'">'.$project->name.'</a>',
                'desc' => $project->description,
                'values' => [
                    [
                        'from' => strtotime($project->date) * 1000,
                        'to' => strtotime($project->execution_period) * 1000,
                        'label' => $project->name,
                        'customClass' =>  $customClass,
                        'link' => '/projects/'.$project->id,
                    ]
                ]
            ];
        }
      ?>
      $(function() {
            "use strict";

            var demoSource = <?php echo json_encode($demoSource, JSON_PRETTY_PRINT); ?>;
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
                itemsPerPage: 10,
                scrollToToday: false,
                useCookie: true,
                onItemClick: function(data) {
                    alert(data.link);
                },
                onAddClick: function(dt, rowId) {
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
                content: '{{__('Here`s some useful information.')}}',
                trigger: "hover",
                placement: "auto right"
            });

            prettyPrint();

        });
    
    </script>
@endsection