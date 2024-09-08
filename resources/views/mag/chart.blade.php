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
            <h1>{{__('Chart')}}</h1>
            <a href="{{ route('mag.show',$magtable) }}" class="btn btn-light w-100">{{__('Back')}}</a>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
                  <script type="text/javascript">
                  
                
                  google.charts.load('current', {packages: ['corechart', 'line']});
google.charts.setOnLoadCallback(drawBasic);

function drawBasic() {

      var data = new google.visualization.DataTable();
      data.addColumn('number', 'X');
      @foreach($firstrows as $firstrow)
        data.addColumn('number', '{{$firstrow}}');
      @endforeach
   
      data.addRows(
        [
        @foreach($final as $rows)
        [
            @foreach($rows as $row){{$row}},@endforeach
        ],
        @endforeach
        
        ]
      );

      var options = {
        hAxis: {
          title: '{{__('Time')}}'
        },
        vAxis: {
          title: '{{$magtable->name}}'
        }
      };

      var chart = new google.visualization.LineChart(document.getElementById('chart_div'));

      chart.draw(data, options);
    }
                </script>
                  <div id="chart_div" ></div>
              </div>
        </div>
    </div>
@endsection