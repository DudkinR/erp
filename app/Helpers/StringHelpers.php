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
    // убираем пробелы со слага
    public static function slugifyNoSpace(string $string): string
    {
        return Str::slug($string, '-');
    }
   public static  function generateSlug($text) {
        $abc_uk = ['а','б','в','г','ґ','д','е','є','ж','з','и','і','ї','й','к','л','м','н','о','п','р','с','т','у','ф','х','ц','ч','ш','щ','ь','ю','я','А','Б','В','Г','Ґ','Д','Е','Є','Ж','З','И','І','Ї','Й','К','Л','М','Н','О','П','Р','С','Т','У','Ф','Х','Ц','Ч','Ш','Щ','Ь','Ю','Я','1','2','3','4','5','6','7','8','9','0',' ','/',':'];
        $abc_en = ['a','b','v','h','g','d','e','ye','zh','z','y','i','yi','y','k','l','m','n','o','p','r','s','t','u','f','kh','ts','ch','sh','shch','`','yu','ya','A','B','V','H','G','D','E','Ye','Zh','Z','Y','I','Yi','Y','K','L','M','N','O','P','R','S','T','U','F','Kh','Ts','Ch','Sh','Shch','`','Yu','Ya','1','2','3','4','5','6','7','8','9','0','-','-','-'];
        $bad_symbols = ['`', '~', '!', '@', '#', '$', '%', '^', '&', '*', '(', ')', '=', '+', '[', ']', '{', '}', ';',  '"', "'", ',', '<', '>', '.',  '?', '\\', '|'];
    
        // Перевод символов из кириллицы в латиницу
        $slug = str_replace($abc_uk, $abc_en, $text);
    
        // Замена плохих символов на тире
        $slug = str_replace($bad_symbols, '-', $slug);
    
        // Преобразование строки в нижний регистр
        $slug = strtolower($slug);
    
        // Замена всех неалфавитно-цифровых символов и повторяющихся тире одним тире
        $slug = preg_replace('/[^a-z0-9\-]+/', '-', $slug);
        $slug = preg_replace('/-+/', '-', $slug);
    
        // Удаление начальных и конечных тире
        $slug = trim($slug, '-');
    
        return $slug;
    }
}