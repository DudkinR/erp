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
        
        // master_doc
        if(!Schema::hasTable('master_doc')) {
            Schema::create('master_doc', function (Blueprint $table) {
                $table->id();
                $table->foreignId('master_id')->constrained('master');
                $table->foreignId('doc_id')->constrained('docs');
                // docs number
                $table->string('number');
                // name 
                $table->string('name');
                $table->timestamps();
            });
        }
       
     
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('master_doc');
    }
};
