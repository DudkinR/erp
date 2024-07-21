@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
            <h1>{{__('Organomics')}}</h1>
                <a class="text-right" href="{{ route('organomic.create') }}">{{__('Add')}}</a>
            </div>
        </div> 
        <div class="row">
            <div class="col-md-12">
                <?php
                  function effective($id_user, $personals, $rooms)
                {
                    // Знайти працівника за ідентифікатором
                    $personal = null;
                    foreach ($personals as $person) {
                        if ($person['id'] == $id_user) {
                            $personal = $person;
                            break;
                        }
                    }
                
                    // Якщо працівника не знайдено, повернути 0
                    if ($personal === null) {
                        return 0;
                    }
                
                    // Знайти кімнату за назвою
                    $room = null;
                    foreach ($rooms as $rm) {
                        if ($rm['name'] == $personal['room']) {
                            $room = $rm;
                            break;
                        }
                    }
                
                    // Якщо кімнату не знайдено, повернути 0
                    if ($room === null) {
                        return 0;
                    }
                
                    // Порахувати кількість людей у кімнаті
                    $peopleInRoom = 0;
                    foreach ($personals as $person) {
                        if ($person['room'] == $room['name']) {
                            $peopleInRoom++;
                        }
                    }
                
                    // Порахувати кількість людей на один телефон
                    $phones = [];
                    foreach ($personals as $person) {
                        if (!isset($phones[$person['phone']])) {
                            $phones[$person['phone']] = 0;
                        }
                        $phones[$person['phone']]++;
                    }
                
                    // Визначити ефективність
                    $coefficients = [
                        1, // Коефіцієнт за наявність приміщення
                        ($room['area'] / $peopleInRoom >= 3) ? 1 : 0, // Коефіцієнт за площу
                        (isset($phones[$personal['phone']]) && $phones[$personal['phone']] <= 3) ? 1 : 0, // Коефіцієнт за телефон
                        $room['air_conditioned'] ? 1 : 0 // Коефіцієнт за кондиціонування
                    ];
                
                    // Додатковий коефіцієнт для приміщення на одному поверсі одного будинку
                    $sameFloorSameBuilding = 1;
                    foreach ($rooms as $rm) {
                        if ($rm['building'] == $room['building'] && $rm['floor'] == $room['floor'] && $rm['name'] != $room['name']) {
                            $sameFloorSameBuilding = 1.1;
                            break;
                        }
                    }
                
                    $totalCoefficients = array_sum($coefficients);
                    $efficiency = ($totalCoefficients / count($coefficients)) * $sameFloorSameBuilding;
                
                    return $efficiency;
                }
                
                function effective_group($group, $personals, $rooms)
                {
                    $efficiency = 0;
                    $count = 0;
                    foreach ($personals as $person) {
                        if ($person['group'] == $group) {
                            $efficiency += effective($person['id'], $personals, $rooms);
                            $count++;
                        }
                    }
                    return $count ? $efficiency / $count : 0;
                }
                
                function effective_department($department, $personals, $rooms)
                {
                    $efficiency = 0;
                    $count = 0;
                    foreach ($personals as $person) {
                        if ($person['department'] == $department) {
                            $efficiency += effective($person['id'], $personals, $rooms);
                            $count++;
                        }
                    }
                    return $count ? $efficiency / $count : 0;
                }

                ?>

            </div>
        </div>    
        <div class="row">
            <?php
                            
                foreach ($personals as $person) {
                    echo "Ефективність працівника з ID {$person['id']}: " . effective($person['id'], $personals, $rooms) . "<br>";
                }
                
                foreach (['A', 'B'] as $group) {
                    echo "Ефективність групи {$group}: " . effective_group($group, $personals, $rooms) . "<br>";
                }
                
                foreach (['IT'] as $department) {
                    echo "Ефективність відділу {$department}: " . effective_department($department, $personals, $rooms) . "<br>";
                }
            ?>
        </div>    
    </div>
@endsection