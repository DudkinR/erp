<?php
namespace App\Helpers;
use App\Models\Position;
use App\Models\Structure;

abstract class Helpers
{
    // функция выбора массива позиций должностей всех подчиненых
    public static function getSubordinatePositions($positions)
    {
        $ps = [];
        foreach ($positions as $position) {
            // Добавляем текущую позицию в массив
            $ps[] = $position->id;
            // Находим все подчиненные структуры
            $structuries = $position->structuries;
            foreach ($structuries as $structury) {
                // Находим все подчиненные позиции
                $subordinate_positions = $structury->positions;
                foreach ($subordinate_positions as $subordinate_position) {
                    $ps[] = $subordinate_position->id;
                }
            }
        }
        // Возвращаем только уникальные идентификаторы позиций
        return array_unique($ps);
    }
}