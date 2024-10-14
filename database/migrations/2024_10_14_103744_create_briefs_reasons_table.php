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
        if (Schema::hasTable('briefs_reasons')) {
            return;
        }
        Schema::create('briefs_reasons', function (Blueprint $table) {
            $table->id();
            $table->foreignId('brief_id')->constrained('briefs')->onDelete('cascade');
            $table->foreignId('reason_id')->constrained('types')->onDelete('cascade');        

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {  
        if (!Schema::hasTable('briefs_reasons')) {
        return;
        }
        Schema::dropIfExists('briefs_reasons');
    }
};
