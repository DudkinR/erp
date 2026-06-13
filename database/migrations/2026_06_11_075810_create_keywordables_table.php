<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
         if (!Schema::hasTable('keywordables')){
            Schema::create('keywordables', function (Blueprint $table) {
                $table->id();
                $table->foreignId('keyword_id')->constrained()->onDelete('cascade');
                
                // ЗМІНІТЬ ТУТ: замість $table->morphs('keywordable') напишіть:
               $table->uuidMorphs('keywordable');

            });
         }

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('keywordables');
    }
};
