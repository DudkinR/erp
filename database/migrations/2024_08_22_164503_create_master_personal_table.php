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
         // master_personal
         if(!Schema::hasTable('master_personal')) {
            Schema::create('master_personal', function (Blueprint $table) {
                $table->id();
                $table->foreignId('master_id')->constrained('master');
                $table->foreignId('personal_id')->constrained('personal');
                // bool brifing 
                $table->boolean('brifing')->default(false);
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('master_personal');
    }
};
