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
        if (Schema::hasTable('numenclature_doc')) {
           // delete table
            Schema::dropIfExists('numenclature_doc');
        }
        Schema::create('numenclature_doc', function (Blueprint $table) {
            $table->id();
            // foreignId('stage_id')->constrained();
            $table->foreignId('nomenclature_id')->constrained('nomenclature');
            $table->foreignId('doc_id')->constrained('documentations');

        });
    }
    

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (!Schema::hasTable('numenclature_doc')) {
            return;
        }
        Schema::dropIfExists('numenclature_doc');
    }
};
