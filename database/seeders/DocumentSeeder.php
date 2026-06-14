<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Document;


class DocumentSeeder extends Seeder
{
    public function run()
    {
        // створюємо кілька організацій (Division)
        $documentFactory = new \Database\Factories\DocumentFactory();
        for ($i = 0; $i < 50; $i++) {
            Document::create($documentFactory->definition());
        }
    }
}
