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
        if (Schema::hasTable('category_doc')) {
            Schema::dropIfExists('category_doc');
        }
        Schema::create('category_doc', function (Blueprint $table) {
            $table->id();
            $table->foreignId('doc_id')->constrained('documentations');
            $table->foreignId('category_id')->constrained('category');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (!Schema::hasTable('category_doc')) {
            return;
        }
        Schema::dropIfExists('category_doc');
    }
};
