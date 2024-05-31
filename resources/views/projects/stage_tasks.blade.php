@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h1>{{ __('Show') }}</h1>
                <a class="text-right" href="{{ route('projects.index') }}">
                    {{ __('Back') }}
                </a>
                <a href="{{ route('projects.stage_tasks_print', [$project_id, $stage_id]) }}" class="btn btn-primary" target="_blank" >{{ __('Print') }}</a>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <table class="table">
                    <thead>
                        <tr>
                            <th>{{ __('Name') }} / {{ __('Description') }}</th>
                            <th>{{ __('Count') }}</th>
                            @foreach($mass_print['positions'] as $position)
                                <th>{{ $position->name }}</th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($mass_print['data'] as $task)
                            <tr >
                                <td>
                                    <h6>{{ $task['name'] }}</h6>
                                    <p>{{ $task['description'] }}</p>
                                </td>
                                <td>{{ $task['count'] }}</td>
                                @foreach($mass_print['positions'] as $position)
                                    <td class = "text-center"
                                        @if (isset($task[$position->id]) && $task[$position->id]['status'] == 'new')
                                            style="background-color: #fffb11;"
                                        @elseif(isset($task[$position->id]) && $task[$position->id]['status'] =='problem')
                                            style="background-color: #FF6347;"
                                        @else
                                            style="background-color: red;"
                                        @endif
                                    > 
                                        @if(isset($task[$position->id]))
                                            {{ $task[$position->id]['status'] }} <br>
                                            @foreach($task[$position->id]['images'] as $image)
                                              <img src="{{ $image->path }}" alt="{{ $task[$position->id]['real_end_date'] }}" style="width: 50px; ">
                                            @endforeach
                                        @endif
                                    </td>
                                @endforeach
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <div class="modal fade" id="modal" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-body">
                                <img src="" alt="" style="width: 100%;">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        // если наводим на картинку, то увеличиваем ее размер в реальный размер в модальном окне
        $(document).ready(function() {
            $('img').click(function() {
                $('#modal').modal('show');
                $('#modal img').attr('src', $(this).attr('src'));
            });
        });
    </script>
@endsection
