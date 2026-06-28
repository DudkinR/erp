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
          if (!Schema::hasTable('document_process')){
            Schema::create('document_process', function (Blueprint $table) {
                $table->id();
                
                // Зв'язок з таблицею processes
                $table->foreignId('process_id')
                    ->constrained()
                    ->onDelete('cascade');
                    
                // Зв'язок з таблицею documents
                $table->foreignId('document_inv_no')
                    ->constrained()
                    ->onDelete('cascade');

                $table->timestamps();
            });
          }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
         if (Schema::hasTable('document_process')){
        Schema::dropIfExists('document_process');
         }
    }
};
