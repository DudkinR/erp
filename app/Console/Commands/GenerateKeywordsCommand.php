<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Kndk;
use App\Models\Process;
use App\Models\Document;
use App\Models\Keyword;
use Illuminate\Support\Str;

class GenerateKeywordsCommand extends Command
{
    // Команда для запуска в терминале
    protected $signature = 'keywords:generate';
    protected $description = 'Генерація ключових слів з усіх КНДК (включаючи назву та код), процесів та документів';

    public function handle()
    {
        $this->info('=== СТАРТ ГЛОБАЛЬНОЇ ГЕНЕРАЦІЇ КЛЮЧОВИХ СЛІВ ===');

        // Базовий набір стоп-слів (сміттєві слова, які не повинні ставати тегами)
        $stopWords = [
            'і', 'й', 'та', 'але', 'чи', 'або', 'що', 'як', 'це', 'про', 'на', 'в', 'у', 'за', 'до', 'для', 'від', 
            'під', 'над', 'перед', 'по', 'через', 'при', 'біля', 'з', 'із', 'зі', 'між', 'без', 'якщо', 'тому', 
            'все', 'всі', 'його', 'її', 'їх', 'процес', 'документ', 'інструкція', 'положення', 'опис', 'назва',
            'короткий', 'зміст', 'згідно', 'відповідно', 'такі', 'така', 'який', 'яка', 'яке', 'які'
        ];

        // Обробляємо КНДК порціями по 100 штук, завантажуючи пов'язані процеси та документи
        Kndk::with(['processes', 'documents'])->chunk(100, function ($kndks) use ($stopWords) {
            foreach ($kndks as $kndk) {
                $this->line("Обробка КНДК ID: {$kndk->id} ({$kndk->full_code})");

                // Масив для збору ВСІХ тегів (і власних, і від процесів/документів) для цього КНДК
                $allKndkKeywordIds = [];

                // 1. Збір та генерація слів із самого КНДК: беремо КОД + ІМ'Я (НАЗВУ)
                $kndkRawText = ($kndk->full_code ?? '') . ' ' . ($kndk->name ?? '');
                $kndkOwnWords = $this->extractCleanWords($kndkRawText, $stopWords);
                
                foreach ($kndkOwnWords as $word) {
                    $keyword = Keyword::firstOrCreate(['name' => $word]);
                    $allKndkKeywordIds[] = $keyword->id;
                }

                // 2. ОБРОБКА ПРОЦЕСІВ ЦЬОГО КНДК
                foreach ($kndk->processes as $process) {
                    // Збираємо дані всюди: з назви та опису процесу
                    $processRawText = ($process->name ?? '') . ' ' . ($process->description ?? '');
                    $processWords = $this->extractCleanWords($processRawText, $stopWords);

                    $processKeywordIds = [];
                    foreach ($processWords as $word) {
                        $keyword = Keyword::firstOrCreate(['name' => $word]);
                        $processKeywordIds[] = $keyword->id;
                        $allKndkKeywordIds[] = $keyword->id; // Дублюємо в загальний кошик КНДК
                    }

                    // Прив'язуємо теги безпосередньо до процесу
                    if (!empty($processKeywordIds)) {
                        $process->keywords()->sync(array_unique($processKeywordIds));
                    }
                }

                // 3. ОБРОБКА ДОКУМЕНТІВ ЦЬОГО КНДК
                foreach ($kndk->documents as $doc) {
                    // Збираємо дані всюди: з назви, опису/змісту та організації
                    $docRawText = ($doc->name ?? '') . ' ' . ($doc->short_content ?? '') . ' ' . ($doc->organization ?? '');
                    $docWords = $this->extractCleanWords($docRawText, $stopWords);

                    $docKeywordIds = [];
                    foreach ($docWords as $word) {
                        $keyword = Keyword::firstOrCreate(['name' => $word]);
                        $docKeywordIds[] = $keyword->id;
                        $allKndkKeywordIds[] = $keyword->id; // Дублюємо в загальний кошик КНДК
                    }

                    // Прив'язуємо теги безпосередньо до документа
                    if (!empty($docKeywordIds)) {
                        $doc->keywords()->sync(array_unique($docKeywordIds));
                    }
                }

                // 4. ПРИВ'ЯЗКА ВСІХ КЛЮЧОВИХ СЛІВ ДО КНДК
                // Сюди увійшли: ім'я/код КНДК + слова всіх його процесів + слова всіх його документів
                if (!empty($allKndkKeywordIds)) {
                    $kndk->keywords()->sync(array_unique($allKndkKeywordIds));
                }
            }
        });

        $this->info('=== ГЕНЕРАЦІЮ ТА ВЗАЄМОПРИВ\'ЯЗКУ УСПІШНО ЗАВЕРШЕНО ===');
        return Command::SUCCESS;
    }

    /**
     * Допоміжний метод: Очищення тексту та виділення основ (коренів) слів
     */
    private function extractCleanWords(?string $text, array $stopWords): array
    {
        if (empty(trim($text))) {
            return [];
        }

        // Переводимо в нижній регістр з урахуванням кирилиці
        $lowerText = mb_strtolower($text, 'UTF-8');

        // Витягуємо лише чисті слова та цифри, прибираючи дефіси, дужки, крапки тощо
        preg_match_all('/[a-zA-Zа-яієїґА-ЯІЄЇҐ0-9]+/u', $lowerText, $matches);
        $rawWords = $matches[0] ?? [];

        $cleanWords = [];
        foreach ($rawWords as $word) {
            $word = trim($word);

            // Фільтрація стоп-слів та надто коротких обрізків (менше 3 символів), якщо це не число
            if (in_array($word, $stopWords) || (mb_strlen($word, 'UTF-8') < 3 && !is_numeric($word))) {
                continue;
            }

            // Базовий український стемінг (відсікання флексій/закінчень для отримання універсального кореня)
            $stemmed = preg_replace('/(ий|ій|ою|ею|и|і|е|а|я|у|ю|ом|ем|ів|ам|ям|ами|ями|их|ові|еві|ення|енню|енням|иця|иці|ицю|ями|ях)$/u', '', $word);
            
            // Якщо після стемінгу слово лишилося нормальної довжини, беремо основу, інакше оригінал
            $finalWord = (mb_strlen($stemmed, 'UTF-8') >= 3 || is_numeric($stemmed)) ? $stemmed : $word;
            
            $cleanWords[] = $finalWord;
        }

        // Повертаємо масив унікальних слів для поточної сутності
        return array_unique($cleanWords);
    }
}
