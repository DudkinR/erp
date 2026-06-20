<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use League\Csv\Reader;
use League\Csv\Writer;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Illuminate\Support\Carbon;

class CsvController extends Controller
{
    private $availableFunctions = [
        'none' => 'Не обробляти (залишити як є)',
        'uppercase' => 'Усі літери ВЕЛИКІ (UPPERCASE)',
        'lowercase' => 'усі літери малі (lowercase)',
        'trim' => 'Очистити від подвійних пробілів',
        'last_word' => 'Взяти лише останнє слово (наприклад, підрозділ)',
    ];

    public function index()
    {
        return view('csv.index');
    }

    // 1. AJAX метод для первинного аналізу файлу
    public function analyze(Request $request)
    {
        $request->validate([
            'file' => 'required|file',
            'type_of_file' => 'required|string'
        ]);

        $encoding = $request->input('type_of_file'); // 'utf-8' або 'windows-1251'
        $path = $request->file('file')->store('temp_csv');
        $fullPath = storage_path('app/private/' . $path);

        // Налаштування Reader з пакета league/csv
        $csv = Reader::createFromPath($fullPath, 'r');
        
        // Якщо кодування Windows-1251, додаємо стрім-фільтр для конвертації на льоту в UTF-8
        if ($encoding === 'windows-1251') {
            $csv->appendStreamFilter('convert.iconv.windows-1251/utf-8');
        }

        $csv->setHeaderOffset(0);

        try {
            $headers = $csv->getHeader();
        } catch (\Exception $e) {
            return response()->json(['message' => 'Не вдалося прочитати заголовки CSV. Перевірте файл.'], 422);
        }

        return response()->json([
            'headers' => $headers,
            'path' => $path,
            'functions' => $this->availableFunctions
        ]);
    }

       // 2. Метод обробки та генерації нового CSV
        public function process(Request $request): StreamedResponse
        {
            // 1. Валідація вхідного файлу
            $request->validate([
                'file' => 'required|file|mimes:csv,txt',
                'type_of_file' => 'required|in:windows-1251,utf-8',
            ]);

            $file = $request->file('file');
            $encoding = $request->input('type_of_file');

            // 2. Створення стрім-відповіді для скачування нового файлу
            $response = new StreamedResponse(function () use ($file, $encoding) {
                // Відкриваємо потік для запису в "вихідний буфер" php://output
                $output = fopen('php://output', 'w');
                
                // Відкриваємо завантажений тимчасовий CSV файл для читання
                if (($handle = fopen($file->getRealPath(), 'r')) !== false) {
                    
                    // Читаємо файл рядок за рядком
                                                        
                                        
                    while (($rawRow = fgetcsv($handle, 0, ";")) !== false) {
                        
                        // 1. Очищення кодування, якщо потрібно
                        if ($encoding === 'windows-1251') {
                            $rawRow = array_map(function ($value) {
                                return mb_convert_encoding($value, 'UTF-8', 'Windows-1251');
                            }, $rawRow);
                        }

                        // 2. Видаляємо зайві порожні елементи (через специфіку ком на кінці рядка)
                        $row = array_map('trim', $rawRow);

                        // Скидаємо лапки, якщо лапка випадково застрягла на початку ПІБ чи в кінці табельного
                        if (isset($row[0])) $row[0] = ltrim($row[0], '"');
                        $lastIdx = count($row) - 1;
                        if (isset($row[$lastIdx])) $row[$lastIdx] = rtrim($row[$lastIdx], '", ');

                        // Пропускаємо заголовок таблиці (перший рядок), якщо там є слово "ПІБ"
                        if (isset($row[0]) && str_contains($row[0], 'ПІБ')) {
                            // Додаємо назви для нових колонок у заголовок
                            $row[5] = 'Підрозділ';
                            $row[6] = 'Дата наказу';
                            $row[7] = 'Кількість днів';
                            
                            fputcsv($output, $row, ";");
                            continue;
                        }

                        // Перевіряємо, чи рядок містить потрібну кількість даних (мінімум ПІБ, Посаду, ОРД, Дату прийняття)
                        if (count($row) < 4) {
                            fputcsv($output, $row, ";");
                            continue;
                        }

                        // Ініціалізуємо нові стовпці 5, 6 та 7 порожніми рядками
                        $row[5] = '';
                        $row[6] = '';
                        $row[7] = '';

                        // Стовбець 0 (ПІБ) — не чіпаємо.

                        // Стовбець 1 (Посада + Підрозділ): забираємо СФЗ, ЦГЗ, РЦ тощо в стовбець 5
                        if (!empty($row[1])) {
                            // Замінюємо подвійні пробіли на одинарні та ділимо
                            $posParts = explode(' ', preg_replace('/\s+/', ' ', $row[1]));
                            if (count($posParts) > 1) {
                                $row[5] = array_pop($posParts); // Останнє слово -> стовбець 5
                                $row[1] = implode(' ', $posParts); // Залишок посади повертаємо назад
                            }
                        }

                        // Стовбець 2 (ОРД): витягуємо дату (напр. 14.01.2026) в стовбець 6
                        if (!empty($row[2])) {
                            $ordParts = explode(' ', preg_replace('/\s+/', ' ', $row[2]));
                            if (count($ordParts) > 1) {
                                $row[6] = array_pop($ordParts); // Остання дата -> стовбець 6
                                $row[2] = implode(' ', $ordParts); // Залишок наказу (напр. "189-к") повертаємо назад
                            }
                        }

                        // Стовбець 3 (Дата прийняття) та Стовбець 6 (Дата наказу): вираховуємо різницю
                        if (!empty($row[3]) && !empty($row[6])) {
                            try {
                                // Перетворюємо тексти на об'єкти дат Carbon
                                $dateAccept = Carbon::createFromFormat('d.m.Y', trim($row[3]))->startOfDay();
                                $dateOrder = Carbon::createFromFormat('d.m.Y', trim($row[6]))->startOfDay();

                                // Від дати наказу (стовбець 6) віднімаємо дату прийняття (стовбець 3)
                                $row[7] = $dateAccept->diffInDays($dateOrder, false);
                            } catch (\Exception $e) {
                                $row[7] = 'Помилка дати';
                            }
                        }

                        // Записуємо оновлений рядок назад у форматі з розділювачем крапка з комою (";")
                        fputcsv($output, $row, ";");
                    }
                    
                    fclose($handle);
                }
                
                fclose($output);
            });

            // 3. Налаштування HTTP-заголовків для примусового скачування файлу
            $response->headers->set('Content-Type', 'text/csv; charset=utf-8');
            $response->headers->set('Content-Disposition', 'attachment; filename="processed_' . time() . '.csv"');
            $response->headers->set('Cache-Control', 'no-cache, no-store, must-revalidate');
            $response->headers->set('Pragma', 'no-cache');
            $response->headers->set('Expires', '0');

            return $response;
        }
  


    private function applyTransformation($value, $action)
    {
        switch ($action) {
            case 'uppercase':
                return mb_strtoupper($value, 'UTF-8');
            case 'lowercase':
                return mb_strtolower($value, 'UTF-8');
            case 'trim':
                return trim(preg_replace('/\s+/', ' ', $value));
            case 'last_word':
                $cleanText = trim(preg_replace('/\s+/', ' ', $value));
                $words = explode(' ', $cleanText);
                return end($words) ?: ''; // Витягує "ЦГЗ"
            default:
                return $value;
        }
    }
}
