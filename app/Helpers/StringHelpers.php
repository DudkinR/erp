<?php

namespace App\Helpers;

use Illuminate\Support\Str;

class StringHelpers extends Helpers
{
    public static function slugify(string $string): string
    {
        return Str::slug($string);
    }
    
    public static function abv($text): string
    {
        $abv = '';
        // удаляем № а цифры отсавляем полностью
        $mass_bad = ['№'];
        $text = str_replace($mass_bad, '', $text);
        $words = explode(' ', $text);
        foreach ($words as $word) {

            $firstChar = mb_substr($word, 0, 1, 'UTF-8');
           // если $word цифра
            if (is_numeric($firstChar)) {
                $abv .= $word;
            }
            else {
               $abv .=  mb_strtoupper($firstChar, 'UTF-8');
            }

            
        }
        return $abv;
    }
}