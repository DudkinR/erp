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
        $formattedDate ='';
        if (empty($date)) {
            return $formattedDate;
        }
        $dateTime = DateTime::createFromFormat('d.m.Y', $date);
        $formattedDate = $dateTime->format('Y-m-d');
        return $formattedDate;
    }
}
