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
        if (!Schema::hasTable('doc_types')) {
            // Create the doc_types table
        Schema::create('doc_types', function (Blueprint $table) {
            $table->id();
            // id old db
            $table->integer('old_id')->nullable();
            $table->string('foreign_name')->nullable(); // Наименование документа
            $table->string('national_name')->nullable(); // Можеш додати, якщо є український варіант
            $table->timestamps();
        });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('doc_types')) {
            Schema::dropIfExists('doc_types');
        }
    }
};
