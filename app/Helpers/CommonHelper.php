<?php

// Path: app/Helpers/CommonHelper.php
namespace App\Helpers;
use Illuminate\Support\Str;
use DateTime;


class CommonHelper extends Helpers
{
   // 
   public static function formattedDate($date)
   {
       if (empty($date)) {
           return NULL;
       }
       
       $dateTime = DateTime::createFromFormat('d.m.Y', $date);
       
       // Проверяем, удалось ли преобразовать строку в дату
       if ($dateTime !== false) {
           // Если удалось, возвращаем дату в формате Y-m-d для записи в базу данных
           return $dateTime->format('Y-m-d');
       } else {
           // Если не удалось преобразовать строку в дату, возвращаем NULL
           return NULL;
       }
   }
}
   
